<?php

namespace src\controller\views\app;

use \src\controller\baseViewController;

use \src\controller\entity\paymentController;

use \src\controller\entity\entityContactController;
use \src\controller\entity\paymentTypeController;


class paymentDeleteViewController extends baseViewController
{
    private $list_filters = array(
                                'payment_type' => array(
                                            'type' => 'select',
                                            'caption' => '',
                                            'placeholder' => '',
                                            'width' => '0',	// if 0 uses the rest of the row
                                            'value' => '',
                                            'value_previous' => '',
                                            'chain_childs' => '',
                                            'options' => '',
                                ),
                                'result' => array(
                                            'type' => 'select',
                                            'caption' => '',
                                            'placeholder' => '',
                                            'width' => '0',	// if 0 uses the rest of the row
                                            'value' => '',
                                            'value_previous' => '',
                                            'chain_childs' => '',
                                            'options' => '',
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
     * @Route('/app/payment/delete/id', name='app_payment_delete')
     *
     * @param POST
     *
     * @return object    Twig template
     */
    public function deleteitemAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/paymentDeleteViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = 'paymentEditViewController '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Vars =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($vars, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $form_action = $this->folder.'/payment/delete';

        $this->pagination = $this->utils->request_pagination( $this->pagination );
        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        if ( !isset( $vars['id']) || $vars['id'] == '' || $vars['id'] == '0' )
        {
            $_SESSION['alert'] = array(
                'type'          => 'danger',
                'message'       => $this->lang['ERR_PAYMENT_NOT_EXISTS'],
                'filters'       => $this->list_filters,
                'pagination'    => $this->pagination,
            );
            header('Location: /'.$this->folder.'/'.$this->lang['PAYMENTS_LINK']);
            exit();
        }

        $reg = new paymentController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $reg->getRegbyId( $vars['id'] );

        $data = array(
            'auth_token' => ( isset( $_POST['auth_token']) )? $_POST['auth_token'] : $this->utils->generateFormToken($form_action),
            'submit' => (isset($_POST['btn_submit'])) ? true : false,
            'action' => 'delete',
            'account_options' => '',
            'payment_type_options' => '',
            'result_options' => '',
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

            if ( !sizeof( $error_ajax ) )
            {
                $this->logger->info('==============='.__METHOD__.' Product '.$vars['id'].' | User '.$this->user.' ===================================================');

                $reg->delete();

                $this->pagination['num_page'] = '1';

                // Send success to be displayed
                $response['status'] = 'OK';
                $response['msg'] = $this->lang['PAYMENT_DELETED'];
                $response['action'] = '/'.$this->folder.'/payments';
                echo json_encode($response);
                exit();
            }
            else
            {
                // Errors found

                // Renew CSRF
//                $data['auth_token'] = $this->utils->generateFormToken($form_action);
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
                $reg->setDate( ( $reg->getDate() == '' ) ? NULL : $reg->getDate()->format('d-m-Y') );
            }
            else
            {
                $_SESSION['alert'] = array(
                                            'type'          => 'danger',
                                            'message'       => $this->lang['ERR_PAYMENT_NOT_EXISTS'],
                                            'filters'       => $this->list_filters,
                                            'pagination'    => $this->pagination,
                );
                header('Location: /'.$this->folder.'/'.$this->lang['PAYMENTS_LINK']);
                exit();
            }
        }
        
        // Account select options list
        if ( $reg->getAccount() == '')
        {
            $data['account_options'] .= '<option value="" selected="selected">'.$this->lang['ACCOUNT_SELECT'].'</option>';
            $data['account_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        }
        $filter_select = array(
            'active' => '1'
        );
        if ( $this->group != '1' && $this->group != '2' ) {
            $filter_select['show_to_staff'] = '1';
        }
        $extra_select = 'ORDER BY `name`';
        $entity_contact = new entityContactController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $rows = $entity_contact->getAll( $filter_select, $extra_select);
        foreach ($rows as $row) {
            $data['account_options'] .= '<option value="' . $row['id'] . '"' . (( $reg->getAccount() == $row['id']) ? ' selected="selected" ' : '') . '>' . $row["name"] . '</option>';
        }

        // Payment Type select options list
        if ( $reg->getPaymentType() == '' )
        {
            $data['payment_type_options'] .= '<option value="" selected="selected">'.$this->lang['PAYMENT_TYPE_SELECT'].'</option>';
            $data['payment_type_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        }
        $filter_select = array(
            'active' => '1'
        );
        $extra_select = 'ORDER BY `name`';
        $payment_type = new paymentTypeController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $rows = $payment_type->getAll( $filter_select, $extra_select);
        foreach ($rows as $row) {
            $data['payment_type_options'] .= '<option value="' . $row['id'] . '"' . (( $reg->getPaymentType() == $row['id']) ? ' selected="selected" ' : '') . '>' . $row["name"] . '</option>';
        }

        // Result select options list
        if ( $reg->getResult() == '' )
        {
            $data['result_options'] .= '<option value="" selected="selected">'.$this->lang['PAYMENT_RESULT_SELECT']. '</option>';
            $data['result_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        }
        $data['result_options'] .= '<option value = 1 ' . (($reg->getResult() == '1') ? ' selected="selected" ' : '') . '>' . $this->lang['PAYMENT_RESULT_OK'] . '</option>';
        $data['result_options'] .='<option value = 0 ' . (($reg->getResult() == '0') ? ' selected="selected" ' : '') . '>' . $this->lang['PAYMENT_RESULT_NOT_OK'] . '</option>';

        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/paymentForm.html.twig', array(
            'reg' => $reg->getReg(),
            'filters' => $this->list_filters,
            'pagination' => $this->pagination,
            'data' => $data,
            'cancel' => '/'.$this->folder.'/payments',
        ));
    }
}
