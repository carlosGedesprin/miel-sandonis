<?php

namespace src\controller\views\app;

use \src\controller\baseViewController;

use \src\controller\entity\mailQueueController;

class mailqueueDeleteViewController extends baseViewController
{
    private $list_filters = array(
                                'to_name' => array(
                                    'type' => 'text',
                                    'caption' => '',
                                    'placeholder' => '',
                                    'width' => '0',	// if 0 uses the rest of the row
                                    'value' => '',
                                    'value_previous' => '',
                                ),
                                'to_address' => array(
                                    'type' => 'text',
                                    'caption' => '',
                                    'placeholder' => '',
                                    'width' => '0',	// if 0 uses the rest of the row
                                    'value' => '',
                                    'value_previous' => '',
                                ),
                                'template' => array(
                                    'type' => 'select',
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
     * @Route('/app/mail_queuey/delete/id', name='app_mail_queue_delete')
     *
     * @param POST
     *
     * @return object    Twig template
     */
    public function deleteitemAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/mailqueueDeleteViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $form_action = $this->folder.'/mailqueue/delete';

        $this->pagination = $this->utils->request_pagination( $this->pagination );
        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        if ( !isset( $vars['id']) || $vars['id'] == '' || $vars['id'] == '0' )
        {
            $_SESSION['alert'] = array(
                'type'          => 'danger',
                'message'       => $this->lang['ERR_MAIL_QUEUE_NOT_EXISTS'],
                'filters'       => $this->list_filters,
                'pagination'    => $this->pagination,
            );
            header('Location: /'.$this->folder.'/'.$this->lang['MAIL_QUEUES_LINK']);
            exit;
        }

        $reg = new mailQueueController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $reg->getRegbyId( $vars['id'] );

        $data = array(
            'auth_token' => ( isset( $_POST['auth_token']) )? $_POST['auth_token'] : $this->utils->generateFormToken($form_action),
            'submit' => (isset($_POST['btn_submit'])) ? true : false,
            'action' => 'delete',
            'priority_options' => '',
            'template_options' => '',
        );

//$txt = 'POST =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'FILES =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_FILES, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
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

            // Check if has been already sent
            if ( $reg->getSent() != NULL )
            {
                $error_ajax[] = array (
                    'dom_object' => [],
                    'msg' => $this->lang['ERR_MAIL_QUEUE_ALREADY_SENT'],
                );
            }

            if ( !sizeof( $error_ajax ) )
            {
                $this->logger->info('==============='.__METHOD__.' Mail '.$vars['id'].' | User '.$this->user.' ===================================================');

                $this->db->deleteORL( $reg->getTableName(), $this->user, 'id', $reg->getId());

                $this->pagination['num_page'] = '1';

                // Send success to be displayed
                $response['status'] = 'OK';
                $response['msg'] = $this->lang['MAIL_QUEUE_DELETED'];
                $response['action'] = '/'.$this->folder.'/mail_queues';
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
                    'message'=>$this->lang['ERR_MAIL_QUEUE_NOT_EXISTS']
                );
                header('Location: /'.$this->folder.'/'.$this->lang['MAIL_QUEUES_LINK']);
                exit();
            }
        }

        if ( $data['priority_options'] == '' )
        {
            $data['priority_options'] .= '<option value="" selected="selected"><b>' . $this->lang['SELECT'] . '</b></option>';
            $data['priority_options'] .= '<option disabled>' . str_repeat('&#x2500', 16) . '</option>';
        }
        $data['priority_options'] .= '<option value="1"' . (( $reg->getPriority() == '1') ? ' selected="selected" ' : '') . '>'.$this->lang['MAIL_QUEUE_PRIORITY_LOW'].'</option>';
        $data['priority_options'] .= '<option value="3"' . (( $reg->getPriority() == '3') ? ' selected="selected" ' : '') . '>'.$this->lang['MAIL_QUEUE_PRIORITY_NORMAL'].'</option>';
        $data['priority_options'] .= '<option value="5"' . (( $reg->getPriority() == '5') ? ' selected="selected" ' : '') . '>'.$this->lang['MAIL_QUEUE_PRIORITY_HIGH'].'</option>';
        $data['priority_value_text'] = (( $reg->getPriority() == '1') ? $this->lang['MAIL_QUEUE_PRIORITY_LOW'] : (( $reg->getPriority() == '3') ? $this->lang['MAIL_QUEUE_PRIORITY_NORMAL'] : (($reg->getPriority() == '5') ? $this->lang['MAIL_QUEUE_PRIORITY_HIGH'] : '' )));

        // Templates select options list
        if ( $data['template_options'] == '' )
        {
            $data['template_options'] .= '<option value="" selected="selected"><b>' . $this->lang['SELECT'] . '</b></option>';
            $data['template_options'] .= '<option disabled>' . str_repeat('&#x2500', 16) . '</option>';
        }
        $rows = $this->db->querySQL('SELECT DISTINCT `template` FROM `mail_queue`');
        foreach ( $rows as $row)
        {
            $data['template_options'] .= '<option value="' . $row['template'] . '"' . (($reg->getTemplate() == $row['template']) ? ' selected="selected" ' : '') . '>' . $row['template'] . '</option>';
        }

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//fclose($this->myfile);
        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/mailqueueForm.html.twig', array(
            'reg' => $reg->getReg(),
            'filters' => $this->list_filters,
            'pagination' => $this->pagination,
            'data' => $data,
            'cancel' => '/'.$this->folder.'/mail_queues',
        ));
    }
}
