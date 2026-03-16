<?php

namespace  src\controller\views\app;

use \src\controller\baseViewController;

use \src\controller\entity\groupController;

class groupDeleteViewController extends baseViewController
{
    private $list_filters = array(
                                    'name' => array(
                                        'type' => 'text',
                                        'caption' => '',
                                        'placeholder' => '',
                                        'width' => '0',	// if 0 uses the rest of the row
                                        'value' => '',
                                        'value_previous' => '',
                                    ),
    );
    private $pagination = array(
                                'num_page'       => '1',
                                'order'          => 'id',
                                'order_dir'      => 'ASC',
                                'rpp'            => '',
    );

    private $folder = 'app';
    
    /**
     * @Route("/app/group/delete/id", name="app_group_delete")
     *
     * @param POST
     *
     * @return object    Twig template
     */
    public function deleteitemAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/groupDeleteViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $form_action = $this->folder.'/group/delete';

        $this->pagination = $this->utils->request_pagination( $this->pagination );
        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        if ( !isset( $vars['id']) || $vars['id'] == '' || $vars['id'] == '0' )
        {
            $_SESSION['alert'] = array(
                'type'          => 'danger',
                'message'       => $this->lang['ERR_GROUP_NOT_EXISTS'],
                'filters'       => $this->list_filters,
                'pagination'    => $this->pagination,
            );
            header('Location: /'.$this->folder.'/'.$this->lang['GROUPS_LINK']);
            exit;
        }

        $reg = new groupController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $reg->getRegbyId( $vars['id'] );

        $data = array(
            'auth_token' => ( isset( $_POST['auth_token']) )? $_POST['auth_token'] : $this->utils->generateFormToken($form_action),
            'submit' => (isset($_POST['btn_submit'])) ? true : false,
            'action' => 'delete',
            'folder_options' => '',
        );

        $error_ajax = array();

        if ( $data['submit'] )
        {
            // CSRF Token validation
            /*
            $valid = $this->utils->verifyFormToken($form_action, $data['auth_token'], 1000);
            if(!$valid){
                return $this->twig->render('app/'.$this->session->config['app_skin'].'/common/show_message.html.twig', array(
                    'alert_type' => 'danger',
                    'title' => $this->lang['WARNING'],
                    'message' => $this->lang['ERR_BAD_TOKEN'],
                    'redirect' => '/logout',
                ));
            }
            */

            // 1-SuperAdmin 2-Admin 3-Staff 4-Student 5-Teacher 6-Visitor
            $special_group = array( 1, 2, 3, 4, 5, 6);
            if ( in_array( $reg->getId(), $special_group ) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['show_to_staff'],
                    'msg' => $this->lang['ERR_GROUP_SPECIAL'],
                );
            }

            if ( $this->db->fetchOne( 'user', 'id', ['group' => $reg->getId() ] ) )
            {
                $error_ajax[] = array (
                    'dom_object' => [],
                    'msg' => $this->lang['ERR_GROUP_HAS_USERS'],
                );
            }

            if ( !sizeof( $error_ajax ) )
            {
                $this->logger->info('==============='.__METHOD__.' Group '.$vars['id'].' | User '.$this->user.' ===================================================');

                $reg->delete();

                $this->pagination['num_page'] = '1';

                // Send success to be displayed
                $response['status'] = 'OK';
                $response['msg'] = $this->lang['GROUP_DELETED'];
                $response['action'] = '/'.$this->folder.'/'.$this->lang['GROUPS_LINK'];
                echo json_encode($response);
                exit();
            }
            else
            {
                // Errors found

                // Renew CSRF
                //$data['auth_token'] = $this->utils->generateFormToken($form_action);

                $response['status'] = 'KO';
                foreach( $error_ajax as $key => $value )
                {
                    $response['errors'][] = $value;
                }
                // Send errors to be displayed
                echo json_encode($response);
                exit();
            }
        }
        else
        {
            if( !empty( $reg->getId() ) )
            {

            }
            else
            {
                $_SESSION['alert'] = array(
                    'type'=>'danger',
                    'message'=>$this->lang['ERR_GROUP_NOT_EXISTS']
                );
                header('Location: /'.$this->folder.'/'.$this->lang['GROUPS_LINK']);
                exit();
            }
        }

        // Folder options
        if ( $reg->getFolder() == '' )
        {
            $data['folder_options'] .= '<option value="" selected="selected">'.$this->lang['GROUP_FOLDER_SELECT'].'</option>';
            $data['folder_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        }
        $dir = APP_ROOT_PATH.'/src/controller/views';
        $files = array_diff( scandir($dir), array('.', '..'));
        foreach ($files as $key => $file)
        {
            if ( is_dir("$dir/$file") )
            {
                $data['folder_options'] .= '<option value="'.$file.'"'.(($reg->getFolder() == $file)? ' selected="selected" ' : '').'>'.$file.'</option>';
            }
        }

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//fclose( $this->myfile );
        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/groupForm.html.twig', array(
            'reg' => $reg->getReg(),
            'filters' => $this->list_filters,
            'pagination' => $this->pagination,
            'data' => $data,
            'cancel' => '/'.$this->folder.'/'.$this->lang['GROUPS_LINK'],
        ));
    }
}
