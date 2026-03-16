<?php

namespace  src\controller\views\app;


class leadDeleteViewController extends \src\controller\baseViewController
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
     * @Route('/app/lead/delete/id', name='app_lead_delete')
     *
     * @param POST
     *
     * @return object    Twig template
     */
    public function deleteitemAction( $vars )
    {
//$myfile = fopen(APP_ROOT_PATH.'/var/logs/leadDeleteViewController'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = 'leadDeleteViewController '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($myfile, $txt);
//$txt = 'POST =========='.PHP_EOL; fwrite($myfile, $txt);
//fwrite($myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($myfile, $txt);

        $form_action = $this->folder.'/lead/delete';

        $this->pagination = $this->utils->request_pagination( $this->pagination );
        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        if ( !isset( $vars['id']) || $vars['id'] == '' || $vars['id'] == '0' )
        {
            $_SESSION['alert'] = array(
                'type'          => 'danger',
                'message'       => 'No existe este lead',
                'filters'       => $this->list_filters,
                'pagination'    => $this->pagination,
            );
            header('Location: /'.$this->folder.'/'.$this->lang['LEADS_LINK']);
            exit;
        }

        require_once( APP_ROOT_PATH.'/src/controller/entity/leadController.php');
        $reg = new \src\controller\entity\leadController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $reg->getRegbyId( $vars['id'] );

        $data = array(
            'auth_token' => ( isset( $_POST['auth_token']) )? $_POST['auth_token'] : $this->utils->generateFormToken($form_action),
            'submit' => (isset($_POST['btn_submit'])) ? true : false,
            'action' => 'delete',
            'send_options' => '',
            'blocked_options' => '',
            'customer_options' => '',
        );

        $error_ajax = array();

        if ( $data['submit'] )
        {
            // CSRF Token validation
            /*
            $valid = $this->utils->verifyFormToken($form_action, $data['auth_token'], 1000);
            if(!$valid){
                return $this->twig->render('app/default/common/show_message.html.twig', array(
                    'section' => $this->lang['N8N_LEAD_EDIT'],
                    'alert_type' => 'danger',
                    'title' => $this->lang['WARNING'],
                    'message' => $this->lang['ERR_BAD_TOKEN'],
                    'redirect' => '/logout',
                ));
            }
            */

            if ( !sizeof( $error_ajax ) )
            {
                $this->logger->info('==============='.__METHOD__.' Lead to delete '.$vars['id'].' | User '.$this->user.' ===================================================');

                $reg->delete();

                $this->pagination['num_page'] = '1';

                // Send success to be displayed
                $response['status'] = 'OK';
                $response['msg'] = $this->lang['LEADS_LINK'];
                $response['action'] = '/'.$this->folder.'/'.$this->lang['LEADS_LINK'];
                echo json_encode($response);
                exit();
            }
            else
            {
                // Renew CSRF - It gives issues with ajax and session destroy in startup
                //$data['auth_token'] = $this->utils->generateFormToken($form_action);

                $response['status'] = 'KO';
                foreach( $error_ajax as $key => $value )
                {
                    $response['errors'][] = $value;
                }
//$txt = 'Response on error '.PHP_EOL; fwrite($myfile, $txt);
//fwrite($myfile, print_r($response, TRUE));

                // Send errors to be displayed
                echo json_encode($response);
                exit();
            }
        }
        else
        {
            if ( !empty( $reg->getId() ) )
            {
                //$reg->setEmail( implode( ',' , $reg->getEmail() ) );
                $reg->setSent( ( $reg->getSent() == '' )? NULL : $reg->getSent()->format('d-m-Y H:i:s') );
            }
            else
            {
                $_SESSION['alert'] = array(
                    'type'          => 'danger',
                    'message'       => $this->lang['ERR_LEAD_NOT_EXISTS'],
                    'filters'       => $this->list_filters,
                    'pagination'    => $this->pagination,
                );
                header('Location: /'.$this->folder.'/'.$this->lang['ACCOUNTS_LINK']);
                exit();
            }
        }

        if ( $reg->getMail_1() != '' ) $reg->setMail_1( $reg->getMail_1()->format('d-m-Y H:i:s') );
        if ( $reg->getMail_2() != '' ) $reg->setMail_2( $reg->getMail_2()->format('d-m-Y H:i:s') );
        if ( $reg->getMail_3() != '' ) $reg->setMail_3( $reg->getMail_3()->format('d-m-Y H:i:s') );
        if ( $reg->getMail_4() != '' ) $reg->setMail_4( $reg->getMail_4()->format('d-m-Y H:i:s') );
        if ( $reg->getMail_5() != '' ) $reg->setMail_5( $reg->getMail_5()->format('d-m-Y H:i:s') );
        if ( $reg->getMail_6() != '' ) $reg->setMail_6( $reg->getMail_6()->format('d-m-Y H:i:s') );

        // Send options
        if ( $reg->getSend() == '' )
        {
            $data['send_options'] .= '<option value="" selected="selected"><b>Seleccione</b></option>';
            $data['send_options'] .= '<option disabled>' . str_repeat('&#x2500', 16) . '</option>';
        }
        $data['send_options'] .= '<option value="0"' . (($reg->getSend() == '0') ? ' selected="selected" ' : '') . '>No mandar</option>';
        $data['send_options'] .= '<option value="1"' . (($reg->getSend() == '1') ? ' selected="selected" ' : '') . '>Mandar</option>';

        // Blocked options
        if ( $reg->getBlocked() == '' )
        {
            $data['blocked_options'] .= '<option value="" selected="selected"><b>Seleccione</b></option>';
            $data['blocked_options'] .= '<option disabled>' . str_repeat('&#x2500', 16) . '</option>';
        }
        $data['blocked_options'] .= '<option value="0"' . (($reg->getBlocked() == '0') ? ' selected="selected" ' : '') . '>No</option>';
        $data['blocked_options'] .= '<option value="1"' . (($reg->getBlocked() == '1') ? ' selected="selected" ' : '') . '>Si</option>';

        // Customer options
        if ( $reg->getCustomer() == '' )
        {
            $data['customer_options'] .= '<option value="" selected="selected"><b>Seleccione</b></option>';
            $data['customer_options'] .= '<option disabled>' . str_repeat('&#x2500', 16) . '</option>';
        }
        $data['customer_options'] .= '<option value="0"' . (($reg->getCustomer() == '0') ? ' selected="selected" ' : '') . '>No</option>';
        $data['customer_options'] .= '<option value="1"' . (($reg->getCustomer() == '1') ? ' selected="selected" ' : '') . '>Si</option>';


        return $this->twig->render('app/default/'.$this->folder.'/leadForm.html.twig', array(
            'reg' => $reg->getReg(),
            'filters' => $this->list_filters,
            'pagination' => $this->pagination,
            'data' => $data,
            'cancel' => '/'.$this->folder.'/'.$this->lang['LEADS_LINK'],
        ));
    }
}
