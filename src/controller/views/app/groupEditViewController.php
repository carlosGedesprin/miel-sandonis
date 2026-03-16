<?php

namespace src\controller\views\app;

use \src\controller\baseViewController;

use \src\controller\entity\groupController;

use DateTime;
use DateTimeZone;

class groupEditViewController extends baseViewController
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
     * @Route('/app/group/edit/id', name='app_group_edit')
     *
     * @param POST
     *
     * @return object    Twig template
     */
    public function edititemAction( $vars )
    {
        $this->logger->info('==============='.__METHOD__.' Group '.$vars['id'].' | User '.$this->user.' ===================================================');
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/groupEditViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $form_action = $this->folder.'/group/editor';

        $this->pagination = $this->utils->request_pagination( $this->pagination );
        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        $reg = new groupController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        
        $reg->setId( ( isset( $vars['id'] ) )? $vars['id'] : '0' );
        $reg->setName( $this->utils->request_var( 'name', '', 'ALL', true) );
        $reg->setRole( $this->utils->request_var( 'role', '', 'ALL') );
        $reg->setFolder( $this->utils->request_var( 'folder', '', 'ALL') );
        $reg->setShowToStaff( $this->utils->request_var( 'show_to_staff', '1', 'ALL') );

        $data = array(
            'auth_token'    => ( isset( $_POST['auth_token']) )? $_POST['auth_token'] : $this->utils->generateFormToken($form_action),
            'submit'        => (isset($_POST['btn_submit'])) ? true : false,
            'action'        => ( $reg->getId() == '0' )? 'add' : 'edit',
            'folder_options' => '',
        );

        $error_ajax = array();

//$txt = 'On start function start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'POST =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'REG =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Data =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'On start function end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        if ( $data['submit'] )
        {
            //TODO:Carlos CSRF is not well resolved, when ajax is involved to send the whole form, startup destroys $_SESSION
            // so normal submit will find session and will not log out the user
            /*
            $valid = $this->utils->verifyFormToken($form_action, $data['auth_token'], 5000);
            if (!$valid) {
                return $this->twig->render('app/'.$this->session->config['app_skin'].'/common/show_message.html.twig', array(
                    'alert_type' => 'danger',
                    'title' => $this->lang['WARNING'],
                    'message' => $this->lang['ERR_BAD_TOKEN'],
                    'redirect' => '/logout',
                ));
            }
            */
            if ( empty( $reg->getName() ) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['name'],
                    'msg' => $this->lang['ERR_NAME_NEEDED'],
                );
            }
            else
            {
                if ( strlen( $reg->getName() ) < 2 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['name'],
                        'msg' => sprintf( $this->lang['ERR_NAME_SHORT'], '2' )
                    );
                }
                else if ( strlen( $reg->getName() ) > 30 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['name'],
                        'msg' => sprintf($this->lang['ERR_NAME_LONG'],'30' )
                    );
                }
                else
                {
                    if ( $reg->groupsWithSameName( $reg->getName(), $reg->getId() ) )
                    {
                        $error_ajax[] = array (
                            'dom_object' => ['name'],
                            'msg' => $this->lang['ERR_NAME_EXISTS'],
                        );
                    }
                }
            }

            if ( empty( $reg->getRole() ) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['role'],
                    'msg' => $this->lang['ERR_GROUP_ROLE_NEEDED'],
                );
            }
            else
            {
                if ( strlen( $reg->getRole() ) < 4 )
                {
                $error_ajax[] = array (
                    'dom_object' => ['role'],
                    'msg' => sprintf( $this->lang['ERR_GROUP_ROLE_SHORT'], '4' ),
                );
                }
                else if ( strlen( $reg->getRole() ) > 20 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['role'],
                        'msg' => sprintf( $this->lang['ERR_GROUP_ROLE_LONG'], '20' ),
                    );
                }
                else if ( substr( $reg->getRole(), 0, 5) !=  'ROLE_' )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['role'],
                        'msg' => sprintf( $this->lang['ERR_GROUP_ROLE_NAME_ROLE'], 'ROLE_' ),
                    );
                }
                //elseif ( sizeof( $reg->getAll( ['role' => $reg->getRole()], ' AND id <> '.$reg->getId()) ) )
                elseif ( $reg->groupsWithSameRole( $reg->getRole(), $reg->getId() ) )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['role'],
                        'msg' => $this->lang['ERR_GROUP_ROLE_EXISTS'],
                    );
                }
            }

            if ( empty( $reg->getFolder() ) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['folder'],
                    'msg' => $this->lang['ERR_FOLDER_NEEDED'],
                );
            }
            
            if ( !sizeof( $error_ajax ) )
            {
                // Fields with special treatment
                $reg->setRole( strtoupper( $reg->getRole() ) );

                if ( $data['action'] == 'add' )
                {
                    // new record
                    $reg->persistORL();
                }
                else
                {
                    // Edit record
                    $reg->persistORL();
                }

                // Send success to be displayed
                $response['status'] = 'OK';
                $response['msg'] = $this->lang['GROUP_SAVED'];
                $response['action'] = '/'.$this->folder.'/'.$this->lang['GROUPS_LINK'];
                echo json_encode($response);
                exit();
            }
            else
            {
                // Errors found

                // Renew CSRF
                //$data['auth_token'] = $this->utils->generateFormToken($form_action);

                // Send errors to be displayed
                $response['status'] = 'KO';
                foreach( $error_ajax as $key => $value )
                {
                    $response['errors'][] = $value;
                }
//$txt = 'Errors =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($response['errors'], TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                echo json_encode($response);
                exit();
            }
        }
        else
        {
            // not submit
            if ( $data['action'] == 'add' )
            {
                // new record
            }
            else
            {
                // Edit record
                if ( $reg->getRegbyId( $reg->getId() ) )
                {
//$txt = 'REG =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
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
