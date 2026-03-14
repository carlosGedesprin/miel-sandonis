<?php

namespace src\controller\views\app;

use \src\controller\baseViewController;

use \src\controller\entity\paymentTypeController;

use DateTime;
use DateTimeZone;

class payment_typeEditViewController extends baseViewController
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
     * @Route('/app/payment_type/edit/id', name='app_payment_type_edit')
     *
     * @param POST
     *
     * @return object    Twig template
     */
    public function edititemAction( $vars )
    {
        $this->logger->info('==============='.__METHOD__.' Product Type '.$vars['id'].' | User '.$this->user.' ===================================================');
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/payment_typeEditViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = 'payment_typeEditViewController '.__FUNCTION__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'POST =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'FILES =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_FILES, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'payment_typeEditViewController '.__FUNCTION__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose($this->myfile);
        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']) );
        $form_action = $this->folder.'/payment_type/editor';

        $this->pagination = $this->utils->request_pagination( $this->pagination );
        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        $reg = new paymentTypeController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $reg_original = new paymentTypeController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $reg->setId( ( isset( $vars['id'] ) )? $vars['id'] : '0' );
        $reg->setName( $this->utils->request_var( 'name', '', 'ALL', true) );
        $reg->setScript( $this->utils->request_var( 'script', NULL, 'ALL', true) );
        $reg->setMethod( $this->utils->request_var( 'method', NULL, 'ALL', true) );
        $reg->setImage( $this->utils->request_var( 'image', NULL, 'ALL', true) );
        $reg->setOrdinal( $this->utils->request_var( 'ordinal', '0', 'ALL', true) );
        $reg->setActive( $this->utils->request_var( 'active', '1', 'ALL') );

        $data = array(
            'auth_token'    => ( isset( $_POST['auth_token']) )? $_POST['auth_token'] : $this->utils->generateFormToken($form_action),
            'submit'        => (isset($_POST['btn_submit'])) ? true : false,
            'action'        => ( $reg->getId() == '0' )? 'add' : 'edit',
            'method_options' => '',
            'ordinal_options' => '',
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
                        'msg' => sprintf( $this->lang['ERR_NAME_SHORT'], '2' ),
                    );
                }
                else if ( strlen( $reg->getName() ) > 100 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['name'],
                        'msg' => sprintf( $this->lang['ERR_NAME_LONG'], '100' ),
                    );
                }
                elseif ( $this->db->fetchOne( $reg->getTableName(), 'id', ['name' => $reg->getName() ], ' AND id <> '.$reg->getId() ))
                {
                    $error_ajax[] = array (
                        'dom_object' => ['name'],
                        'msg' => $this->lang['ERR_NAME_EXISTS'],
                    );
                }
            }

            if ( $data['action'] == 'add' )
            {
                if( $reg->getActive() == '' )
                {
                $error_ajax[] = array (
                    'dom_object' => ['active'],
                    'msg' => $this->lang['ERR_ACTIVE_NEEDED'],
                    );
                }
            }
            
            if ( !sizeof( $error_ajax ) )
            {
                if ( $data['action'] == 'add' )
                { 
//$txt = '========== NEW =========='.PHP_EOL; fwrite($this->myfile, $txt);
                    $reg->persist();
                }
                else
                {
//$txt = '========== EDIT =========='.PHP_EOL; fwrite($this->myfile, $txt);

                    if ( $reg->getOrdinal() == 'null' )
                    {
//$txt = 'Ordinal null =========='.PHP_EOL; fwrite($this->myfile, $txt);
                        $reg_original->getRegbyId( $reg->getId() );
//$txt = 'Original REG =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg_original->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                        $reg->setOrdinal( $reg_original->getOrdinal() );
//$txt = 'New Ordinal on REG ======> '.$reg->getOrdinal().PHP_EOL; fwrite($this->myfile, $txt);
                    }

//$txt = 'New REG =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                    $reg->persistORL();
                }

                $reg->reOrderOrdinals();

                // Send success to be displayed
                $response['status'] = 'OK';
                $response['msg'] = $this->lang['PAYMENT_TYPE_SAVED'];
                $response['action'] = '/'.$this->folder.'/payment_types';
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
//$txt = '========== NEW =========='.PHP_EOL; fwrite($this->myfile, $txt);
            }
            else
            {
//$txt = '========== EDIT =========='.PHP_EOL; fwrite($this->myfile, $txt);
                if ( $reg->getRegbyId( $reg->getId() ) )
                {
//$txt = 'REG =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                }
                else
                {
                    $_SESSION['alert'] = array('type'=>'danger', 'message'=>$this->lang['ERR_PAYMENT_TYPE_NOT_EXISTS']);
                    header('Location: /'.$this->folder.'/'.$this->lang['PAYMENT_TYPES_LINK']);
                    exit;

                }
            }
        }

        // method select options list
        if ( $reg->getMethod() == '' )
        {
            $data['method_options'] .= '<option value="" selected="selected">'.$this->lang['SELECT'].'</option>';
            $data['method_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        }
        $data['method_options'] .= '<option value="online"' . (($reg->getMethod() == 'online') ? ' selected="selected" ' : '') . '>onLine</option>';
        $data['method_options'] .= '<option value="bank_transfer"' . (($reg->getMethod() == 'bank_transfer') ? ' selected="selected" ' : '') . '>Bank Transfer</option>';

        if ( $data['action'] != 'add' )
        {
            $data['ordinal_options'] = $reg->getOrdinalOptionsList( $data['action'], $reg->getOrdinal() );
        }

        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/payment_typeForm.html.twig', array(
            'reg' => $reg->getReg(),
            'filters' => $this->list_filters,
            'pagination' => $this->pagination,
            'data' => $data,
            'cancel' => '/'.$this->folder.'/payment_types',
        ));
    }
}
