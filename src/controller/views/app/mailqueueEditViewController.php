<?php

namespace src\controller\views\app;

use \src\controller\baseViewController;

use \src\controller\entity\mailQueueController;

use DateTime;
use DateTimeZone;

class mailqueueEditViewController extends baseViewController
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
     * @Route('/app/mail_queue/edit/id', name='app_mail_queue_edit')
     *
     * @param $_POST array POST data
     *
     * @return object    Twig template
     */
    public function edititemAction( $vars )
    {
         $this->logger->info('==============='.__METHOD__.' Mail '.$vars['id'].' | User '.$this->user.' ===================================================');
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/mailqueueEditViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']) );

        $form_action = $this->folder.'/mailqueue/editor';

        $this->pagination = $this->utils->request_pagination( $this->pagination );
        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        $reg = new mailQueueController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $reg->setId(( isset( $vars['id'] ) )? $vars['id'] : '0' );
//        $reg->setSend( $this->utils->request_var('send', $now, 'ALL') );
        $reg->setSend( $now );
        $reg->setPriority( $this->utils->request_var('priority', '3', 'ALL') ); //Normal
        $reg->setSent( $this->utils->request_var('sent', NULL, 'ALL' ) );
        $reg->setToAddress( $this->utils->request_var('to_address', '', 'ALL' ) );
        $reg->setToName( $this->utils->request_var('to_name', '', 'ALL' ));
        $reg->setCcAddress( $this->utils->request_var('cc_address', '', 'ALL' ) );
        $reg->setCcName( $this->utils->request_var('cc_name', '', 'ALL' ) );
        $reg->setBccAddress( $this->utils->request_var('bcc_address', '', 'ALL' ) );
        $reg->setBccName( $this->utils->request_var('bcc_name', '', 'ALL' ) );
        $reg->setFromAddress( $this->utils->request_var('from_address', $this->session->config['email_system_address'], 'ALL' ) );
        $reg->setFromName( $this->utils->request_var('from_name', $this->session->config['email_system_name'], 'ALL' ) );
        $reg->setTemplate( $this->utils->request_var('template', '', 'ALL' ) );
        $reg->setSubject( $this->utils->request_var('subject', '', 'ALL' ) );
        $reg->setPreheader( $this->utils->request_var('pre_header', '', 'ALL' ) );
        $reg->setLocale( $this->utils->request_var('locale', '', 'ALL' ) );
        $reg->setMessage( $this->utils->request_var('message', '', 'ALL' ) );
        $reg->setHeaders( $this->utils->request_var('headers', '', 'ALL') );
        $reg->setImages( $this->utils->request_var('images', '', 'ALL') );
        $reg->setAssignVars( $this->utils->request_var('assign_vars', '', 'ALL') );
        $reg->setBlockName( $this->utils->request_var('block_name', '', 'ALL'));
        $reg->setAssignBlockVars( $this->utils->request_var('assign_block_vars', '', 'ALL') );
        $reg->setAttached( $this->utils->request_var('attached', '', 'ALL') );
        $reg->setToken($this->utils->request_var('token', '', 'ALL'));

        $data = array(
            'auth_token' => ( isset( $_POST['auth_token']) )? $_POST['auth_token'] : $this->utils->generateFormToken($form_action),
            'submit' => (isset($_POST['btn_submit'])) ? true : false,
            'action' => ( $reg->getId() == '0' )? 'add' : 'edit',
            'priority_options' => '',
            'template_options' => '',
            'priority_value_text' => '',
        );

        $error_ajax = array();

//$txt = 'On start function start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'POST =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'REG =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
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

            if ( empty( $reg->getSend() ) )
            {
                $reg->setSend( $now );
            }

            if ( $reg->getSent() != NULL )
            {
                $_SESSION['alert'] = array(
                    'type' => 'danger',
                    'message' => $this->lang['ERR_MAIL_QUEUE_ALREADY_SENT'],
                    'filters' => $this->list_filters,
                    'pagination' => $this->pagination,
                );
                header('Location: /'.$this->folder.'/'.$this->lang['MAIL_QUEUES_LINK']);
                exit;
            }

            $date_match = '/^[0-9]{2}+-[0-9]{2}+-[0-9]{4}\s[0-9]{2}[:][0-9]{2}[:][0-9]{2}$/';
            if ( empty( $reg->getSend() ) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['send'],
                    'msg' => $this->lang['ERR_MAIL_QUEUE_SEND_DATE_NEEDED'],
                );
            }
            else
            {
                $send = $reg->getSend()->format('d-m-Y H:i:s');
                if ( strlen( $send ) != 19 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['send'],
                        'msg' => $this->lang['ERR_MAIL_QUEUE_SEND_DATE_BAD'],
                    );
                }
            }

            if ( empty( $reg->getPriority() ) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['priority'],
                    'msg' => $this->lang['ERR_MAIL_QUEUE_PRIORITY_NEEDED'],
                );
            }

            if ( empty( $reg->getToName() ) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['to_name'],
                    'msg' => $this->lang['ERR_MAIL_QUEUE_TO_NAME_NEEDED'],
                );
            }
            else
            {
                if ( strlen( $reg->getToName() ) < 2 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['to_name'],
                        'msg' => sprintf($this->lang['ERR_MAIL_QUEUE_TO_NAME_SHORT'], '2'),
                    );
                }
                else if ( strlen( $reg->getToName() ) > 100 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['to_name'],
                        'msg' => sprintf($this->lang['ERR_MAIL_QUEUE_TO_NAME_LONG'], '100'),
                    );
                }
            }

            $match = '/^[A-Za-z0-9-_.+%]+@[A-Za-z0-9-.]+\.[A-Za-z]{2,20}$/';
            if ( empty( $reg->getToAddress() ) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['to_address'],
                    'msg' => $this->lang['ERR_MAIL_QUEUE_TO_ADDRESS_NEEDED'],
                );
            }
            else
            {
                if ( strlen( $reg->getToAddress() ) < 10 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['to_address'],
                        'msg' => sprintf($this->lang['ERR_MAIL_QUEUE_TO_ADDRESS_SHORT'], '10'),
                    );
                }
                else if ( strlen( $reg->getToAddress() ) > 100 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['to_address'],
                        'msg' => sprintf($this->lang['ERR_MAIL_QUEUE_TO_ADDRESS_LONG'], '100'),
                    );
                }
                else if ( !preg_match( $match, $reg->getToAddress() ) )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['to_address'],
                        'msg' => $this->lang['ERR_MAIL_QUEUE_TO_ADDRESS_BAD'],
                    );
                }
            }

            if ( !empty( $reg->getCcName() ) )
            {
                if ( strlen( $reg->getCcName() ) < 3 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['cc_name'],
                        'msg' => sprintf($this->lang['ERR_MAIL_QUEUE_CC_NAME_SHORT'],'3'),
                    );
                }
                else if ( strlen( $reg->getCcName() ) > 100 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['cc_name'],
                        'msg' => sprintf($this->lang['ERR_MAIL_QUEUE_CC_NAME_LONG'],'100')
                    );
                }
            }

            if ( !empty( $reg->getCcAddress() ) )
            {
                if ( strlen( $reg->getCcAddress() ) < 10 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['cc_address'],
                        'msg' => sprintf( $this->lang['ERR_MAIL_QUEUE_CC_ADDRESS_SHORT'],'10' ),
                    );
                }
                else if ( strlen( $reg->getCcAddress() ) > 100 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['cc_address'],
                        'msg' => sprintf($this->lang['ERR_MAIL_QUEUE_CC_ADDRESS_LONG'],'100'),
                    );
                }
            }

            if ( !empty( $reg->getBccName()  ))
            {
                if ( strlen( $reg->getBccName() ) < 3 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['bcc_name'],
                        'msg' => sprintf( $this->lang['ERR_MAIL_QUEUE_BCC_NAME_SHORT'],'3' ),
                    );
                }
                else if ( strlen( $reg->getBccName() ) > 100 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['bcc_name'],
                        'msg' => sprintf( $this->lang['ERR_MAIL_QUEUE_BCC_NAME_LONG'],'100' ),
                    );
                }
            }

            if ( !empty( $reg->getBccAddress() ) )
            {
                if ( strlen( $reg->getBccAddress() ) < 10 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['bcc_address'],
                        'msg' => sprintf( $this->lang['ERR_MAIL_QUEUE_BCC_ADDRESS_SHORT'],'10' ),
                    );
                }
                else if ( strlen( $reg->getBccAddress() ) > 100 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['bcc_address'],
                        'msg' => sprintf( $this->lang['ERR_MAIL_QUEUE_BCC_ADDRESS_LONG'],'100' ),
                    );
                }
            }

            if ( !empty( $reg->getFromName() ) )
            {
                if ( strlen( $reg->getFromName() ) < 3 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['from_name'],
                        'msg' => sprintf( $this->lang['ERR_MAIL_QUEUE_FROM_NAME_SHORT'],'3' ),
                    );
                }
                else if ( strlen( $reg->getFromName() ) > 100 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['from_name'],
                        'msg' => sprintf( $this->lang['ERR_MAIL_QUEUE_FROM_NAME_LONG'],'100' ),
                    );
                }
            }

            if ( !empty( $reg->getFromAddress() ) )
            {
                if ( strlen( $reg->getFromAddress() ) < 3 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['from_address'],
                        'msg' => sprintf($this->lang['ERR_MAIL_QUEUE_FROM_ADDRESS_SHORT'],'3' ),
                    );
                }
                else if ( strlen( $reg->getFromAddress() ) > 100 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['from_address'],
                        'msg' => sprintf( $this->lang['ERR_MAIL_QUEUE_FROM_ADDRESS_LONG'],'100' ),
                    );
                }
            }

            if ( empty( $reg->getSubject() ) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['subject'],
                    'msg' => $this->lang['ERR_MAIL_QUEUE_SUBJECT_NEEDED'], //ERR_SUBJECT_NEEDED
                );
            }
            else
            {
                if ( strlen( $reg->getSubject() ) < 3 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['subject'],
                        'msg' => sprintf( $this->lang['ERR_MAIL_QUEUE_SUBJECT_SHORT'],'3' ),
                    );
                }
                else if ( strlen( $reg->getSubject() ) > 255 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['subject'],
                        'msg' => sprintf( $this->lang['ERR_MAIL_QUEUE_SUBJECT_LONG'],'255'),
                    );
                }
            }

            if ( !empty( $reg->getPreHeader() ) )
            {
                if ( strlen( $reg->getPreHeader() ) < 3 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['pre_header'],
                        'msg' => sprintf( $this->lang['ERR_MAIL_QUEUE_PRE_HEADER_SHORT'],'3'),
                    );
                }
                else if ( strlen( $reg->getPreHeader() ) > 255 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['pre_header'],
                        'msg' => sprintf( $this->lang['ERR_MAIL_QUEUE_PRE_HEADER_LONG'],'255' ),
                    );
                }
            }

            if ( !empty( $reg->getHeaders() ) )
            {
                if ( strlen( $reg->getHeaders() ) < 3 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['headers'],
                        'msg' => sprintf( $this->lang['ERR_MAIL_QUEUE_HEADERS_SHORT'],'3' ),
                    );
                }
                else if ( strlen( $reg->getHeaders() ) > 255 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['headers'],
                        'msg' => sprintf( $this->lang['ERR_MAIL_QUEUE_HEADERS_LONG'],'255'),
                    );
                }
            }

            if ( !empty( $reg->getImages()['images'] ) )
            {
                if ( strlen( $reg->getImages()['images'] ) < 3 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['images'],
                        'msg' => sprintf( $this->lang['ERR_MAIL_QUEUE_IMAGES_SHORT'],'3' ),
                    );
                }
                else if ( strlen ( $reg->getImages()['images'] ) > 255 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['images'],
                        'msg' => sprintf( $this->lang['ERR_MAIL_QUEUE_IMAGES_LONG'],'255' ),
                    );
                }
            }

            if ( !empty( $reg->getBlockName() ) )
            {
                if ( strlen( $reg->getBlockName() ) < 3 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['block_name'],
                        'msg' => sprintf( $this->lang['ERR_MAIL_QUEUE_BLOCK_NAME_SHORT'],'3'),
                    );
                }
                else if ( strlen( $reg->getBlockName() ) > 100 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['block_name'],
                        'msg' => sprintf( $this->lang['ERR_MAIL_QUEUE_BLOCK_NAME_LONG'],'100'),
                    );
                }
            }

            if ( !empty( $reg->getAttached() ) )
            {
                if ( strlen( $reg->getAttached()['attached'] ) < 5 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['attached'],
                        'msg' => sprintf( $this->lang['ERR_MAIL_QUEUE_ATTACHED_SHORT'],'5'),
                    );
                }
                else if ( strlen( $reg->getAttached()['attached'] ) > 100 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['attached'],
                        'msg' => sprintf( $this->lang['ERR_MAIL_QUEUE_ATTACHED_LONG'], '100' ),
                    );
                }
            }

            if ( empty( $reg->getTemplate() ) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['template'],
                    'msg' => $this->lang['ERR_TEMPLATE_NEEDED'],
                );
            }

            if ( $data['action'] == 'add' && empty( $reg->getMessage() ))
            {
                $error_ajax[] = array (
                    'dom_object' => ['message'],
                    'msg' => $this->lang['ERR_MAIL_QUEUE_MESSAGE_NEEDED'],
                );
            }

            if ( !sizeof( $error_ajax ) )
            {
                // Fields with special treatment
              
                if ( $data['action'] == 'add' )
                {
//$txt = '========== NEW =========='.PHP_EOL; fwrite($this->myfile, $txt);
                    $reg->persistORL();
                }
                else
                {
//$txt = '========== EDIT =========='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Date reg ('.$reg->getSend().')'.PHP_EOL; fwrite($this->myfile, $txt);
                    $reg->persistORL();
                }

                // Send success to be displayed
                $response['status'] = 'OK';
                $response['msg'] = $this->lang['MAIL_QUEUE_SAVED'];
                $response['action'] = '/'.$this->folder.'/mail_queues';
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
                        'message'=>$this->lang['ERR_MAIL_QUEUE_NOT_EXISTS']
                    );
                    header('Location: /'.$this->folder.'/'.$this->lang['MAIL_QUEUES_LINK']);
                    exit;
                }
            }
        }
//$txt = 'Before to display'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'REG =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Data =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        if ( $data['priority_options'] == '' )
        {
            $data['priority_options'] .= '<option value="" selected="selected"><b>' . $this->lang['SELECT'] . '</b></option>';
            $data['priority_options'] .= '<option disabled>' . str_repeat('&#x2500', 16) . '</option>';
        }
        $data['priority_options'] .= '<option value="1"' . (( $reg->getPriority() == '1') ? ' selected="selected" ' : '') . '>'.$this->lang['MAIL_QUEUE_PRIORITY_LOW'].'</option>';
        $data['priority_options'] .= '<option value="3"' . (( $reg->getPriority() == '3') ? ' selected="selected" ' : '') . '>'.$this->lang['MAIL_QUEUE_PRIORITY_NORMAL'].'</option>';
        $data['priority_options'] .= '<option value="5"' . (( $reg->getPriority() == '5') ? ' selected="selected" ' : '') . '>'.$this->lang['MAIL_QUEUE_PRIORITY_HIGH'].'</option>';
        $data['priority_value_text'] = ((  $reg->getPriority() == '1') ? $this->lang['MAIL_QUEUE_PRIORITY_LOW'] : ((  $reg->getPriority() == '3') ? $this->lang['MAIL_QUEUE_PRIORITY_NORMAL'] : (( $reg->getPriority() == '5') ? $this->lang['MAIL_QUEUE_PRIORITY_HIGH'] : '' )));

        // Templates select options list
        if ( $data['template_options'] == '' )
        {
            $data['template_options'] .= '<option value="" selected="selected"><b>' . $this->lang['SELECT'] . '</b></option>';
            $data['template_options'] .= '<option disabled>' . str_repeat('&#x2500', 16) . '</option>';
        }
        $rows = $this->db->querySQL('SELECT DISTINCT `template` FROM `mail_queue`');
        foreach ( $rows as $row)
        {
            $data['template_options'] .= '<option value="' . $row['template'] . '"' . (( $reg->getTemplate() == $row['template']) ? ' selected="selected" ' : '') . '>' . $row['template'] . '</option>';
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
