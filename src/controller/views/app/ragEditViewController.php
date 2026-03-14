<?php

namespace src\controller\views\app;

use \src\controller\baseViewController;
use \src\controller\entity\ragController;

use \src\controller\entity\serverController;

use DateTime;
use DateTimeZone;

class ragEditViewController extends baseViewController
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
                                    'account' => array(
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
     * @Route('/app/rag/edit/id', name='app_rag_edit')
     *
     * @param POST
     *
     * @return object    Twig template
     */
    public function edititemAction( $vars )
    {
        $this->logger->info('==============='.__METHOD__.' Rag process '.$vars['id'].' | User '.$this->user.' ===================================================');
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/ragEditViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']) );

        $form_action = $this->folder.'/rag/editor';

        $this->pagination = $this->utils->request_pagination( $this->pagination );
        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        $reg = new ragController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $reg_original = new ragController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $reg->setId(( isset( $vars['id'] ) )? $vars['id'] : '0');
        $reg->setName( $this->utils->request_var( 'name', '', 'ALL', true) );
        $reg->setAccount( $this->utils->request_var( 'account', '', 'ALL') );
        $reg->setBillingAccount( $this->utils->request_var( 'billing_account', '', 'ALL') );
        $reg->setProductSetup( $this->utils->request_var( 'product_setup', '', 'ALL') );
        $reg->setProductRenewal( $this->utils->request_var( 'product_renewal', '', 'ALL') );
        $reg->setPriceSetup( $this->utils->request_var( 'price_setup', '', 'ALL') );
        $reg->setPriceRenewal( $this->utils->request_var( 'price_renewal', '', 'ALL') );
        $reg->setCoupon( $this->utils->request_var( 'coupon', '', 'ALL') );
        $reg->setDateReg( $this->utils->request_var( 'date_reg', $now->format('Y-m-d H:i:s'), 'ALL') );
        $reg->setAutoRenew( $this->utils->request_var( 'auto_renew', '1', 'ALL') );
        $reg->setDateStart( $this->utils->request_var( 'date_start', '', 'ALL') );
        $reg->setDateEnd( $this->utils->request_var( 'date_end', '', 'ALL') );
        $reg->setAgent( $this->utils->request_var( 'agent', '', 'ALL') );
        $reg->setServer( $this->utils->request_var( 'server', '', 'ALL') );
        $reg->setAddress( $this->utils->request_var( 'address', '', 'ALL', true) );
        $reg->setFolder( $this->utils->request_var( 'folder', '', 'ALL', true) );
        $reg->setUsername( $this->utils->request_var( 'username', '', 'ALL', true) );
        $reg->setPassword( $this->utils->request_var( 'password', '', 'ALL', true) );
        $reg->setActive( $this->utils->request_var( 'active', '1', 'ALL') );

        $data = array(
            'auth_token'    => ( isset( $_POST['auth_token']) )? $_POST['auth_token'] : $this->utils->generateFormToken($form_action),
            'submit'        => ( isset($_POST['btn_submit'])) ? true : false,
            'action'        => ( $reg->getId() == '0' )? 'add' : 'edit',
            'account_options' => '',
            'billing_account_options' => '',
            'product_setup_options' => '',
            'product_renewal_options' => '',
            'agent_options' => '',
            'server_options' => '',
            'coupon_options' => '',
            'get_product_url' => '/api/get_product',
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
//$txt = 'Submit ========>'.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
            //TODO:Carlos CSRF is not well resolved, when ajax is involved to send the whole form, startup destroys $_SESSION
            // so normal submit will find session and will not log out the user
            /*
            $valid = $this->utils->verifyFormToken($form_action, $data['auth_token'], 5000);
            if (!$valid) {
                return $this->twig->render('app/'.$this->session->config['app_skin'].'/common/show_message.html.twig', array(
                    'section' => $this->lang['RAG_EDIT'],
                    'alert_type' => 'danger',
                    'title' => $this->lang['WARNING'],
                    'message' => $this->lang['ERR_BAD_TOKEN'],
                    'redirect' => '/logout',
                ));
            }
            */

            if ( empty( $reg->getAccount() ) )
            {
                $error_ajax[] = array(
                    'dom_object' => ['account'],
                    'msg' => $this->lang['ERR_ACCOUNT_NEEDED'],
                );
            }

            if ( empty( $reg->getBillingAccount() ) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['billing_account'],
                    'msg' => $this->lang['ERR_ACCOUNT_BILLING_ACCOUNT_NEEDED'],
                );
            }

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
//$txt = 'account '.$reg->getAccount().PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
                    if ( !empty( $reg->getAccount() ) )
                    {
//$txt = 'Verification name duplicated'.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
                        //ragsWithSameName( $name, $type_key, $key, $account )
                        if ( $reg->ragsWithSameName( $reg->getName(), 'id', $reg->getId(), $reg->getAccount() ) )
                        {
                            $error_ajax[] = array (
                                'dom_object' => ['name'],
                                'msg' => $this->lang['ERR_NAME_EXISTS'],
                            );
                        }
                    }
                }
            }
//$txt = 'Name OK'.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);

//$txt = 'Product to validate => ('.$reg->getProduct().') Date end ('.$reg_original->getDateEnd().')'.PHP_EOL; fwrite($this->myfile, $txt);
            if ( empty( $reg->getProductSetup() ) && empty( $reg->getProductRenewal() ) )
            {
                $error_ajax[] = array(
                    'dom_object' => ['product_setup', 'product_renewal'],
                    'msg' => $this->lang['ERR_PRODUCT_NEEDED']
                );
            }

            if ( !empty( $reg->getAddress() ) && !empty( $reg->getUsername() ) && !empty( $reg->getPassword() ) )
            {
                //$ftp_conn = @ftp_ssl_connect( $rag->getAddress() );
                $ftp_conn = @ftp_connect( $reg->getAddress() );
                if ( !$ftp_conn )
                {
                    $error_ajax[] = array(
                        'dom_object' => ['rag'],
                        'msg' => $this->lang['ERR_RAG_BAD_CREDENTIALS'],
                    );
                }
                else
                {
                    if ( @ftp_login( $ftp_conn, $reg->getUsername(), $reg->getPassword() ) )
                    {
//$txt = 'FTP Connection established.'.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
                        @ftp_pasv( $ftp_conn, true );

                        if ( !empty( $reg->getFolder() ) )
                        {
                            if ( strlen( $reg->getFolder() ) < 2 )
                            {
                                $error_ajax[] = array (
                                    'dom_object' => ['folder'],
                                    'msg' => sprintf( $this->lang['ERR_RAG_FOLDER_NAME_SHORT'], '2' )
                                );
                            }
                            else if ( strlen( $reg->getFolder() ) > 100 )
                            {
                                $error_ajax[] = array (
                                    'dom_object' => ['folder'],
                                    'msg' => sprintf($this->lang['ERR_RAG_FOLDER_NAME_LONG'],'100' )
                                );
                            }
                            else
                            {
                                $items_on_ftp = @ftp_rawlist( $ftp_conn, "." );
//$txt = 'List of ftp =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($items_on_ftp, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

                                $folder_exists = false;
                                foreach ( $items_on_ftp as $item_on_tfp_temp )
                                {
                                    $parts = preg_split("/\s+/", $item_on_tfp_temp, 9);
                                    if ( $parts[0][0] === 'd' && $parts[8] === $reg->getFolder() )
                                    {
//$txt = 'folder '.$reg->getFolder().' => Exists'.PHP_EOL; fwrite($this->myfile, $txt);
                                        $folder_exists = true;
                                        break;
                                    }
                                }

                                if ( !$folder_exists )
                                {
                                    if ( @ftp_mkdir( $ftp_conn, $reg->getFolder() ) )
                                    {
//$txt = 'folder '.$reg->getFolder().' => Created'.PHP_EOL; fwrite($this->myfile, $txt);
                                    }
                                    else
                                    {
//$txt = 'folder '.$reg->getFolder().' => NOT created'.PHP_EOL; fwrite($this->myfile, $txt);
                                        @ftp_close( $ftp_conn );
                                        $error_ajax[] = array(
                                            'dom_object' => ['folder'],
                                            'msg' => $this->lang['ERR_RAG_FOLDER_NOT_CREATED'],
                                        );
                                    }
                                }
                            }
                        }
                    }
                    else
                    {
                        @ftp_close( $ftp_conn );
                        $error_ajax[] = array(
                            'dom_object' => ['rag'],
                            'msg' => $this->lang['ERR_RAG_BAD_CREDENTIALS'],
                        );
                    }
                }
            }

//$txt = 'Errors ('.sizeof( $error_ajax ).')'.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
            if ( !sizeof( $error_ajax ) )
            {
//$txt = 'No errors ========>'.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);

                if ( $data['action'] == 'add' )
                {
//$txt = '========== ADD =========='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Reg =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

                    $reg->setDateReg( ( !empty( $reg->getDateReg() ) )? DateTime::createFromFormat('d-m-Y H:i:s', $reg->getDateReg(), new DateTimeZone($this->session->config['time_zone'])) : NULL );
                    $reg->setDateStart( ( !empty( $reg->getDateStart() ) )? DateTime::createFromFormat('d-m-d', $reg->getDateStart(), new DateTimeZone($this->session->config['time_zone'])) : NULL );
                    $reg->setDateEnd( ( !empty( $reg->getDateEnd() ) )? DateTime::createFromFormat('d-m-d', $reg->getDateEnd(), new DateTimeZone($this->session->config['time_zone'])) : NULL );

                    $reg->persistORL();

                    $reg->setRagKey( md5( $reg->getId().$reg->getName() ) );
                    $reg->persist();
                }
                else
                {
//$txt = '========== EDIT =========='.PHP_EOL; fwrite($this->myfile, $txt);
                    if ( !empty( $reg->getDateReg() ) )
                    {
                        $reg->setDateReg( DateTime::createFromFormat('d-m-Y H:i:s', $reg->getDateReg(), new DateTimeZone($this->session->config['time_zone'])));
                    }
                    if ( !empty($reg->getDateStart()) )
                    {
                        $reg->setDateStart( DateTime::createFromFormat('d-m-Y', $reg->getDateStart(), new DateTimeZone($this->session->config['time_zone'])) );
                    }
                    if ( !empty($reg->getDateEnd()) )
                    {
                        $reg->setDateEnd( DateTime::createFromFormat('d-m-Y', $reg->getDateEnd(), new DateTimeZone($this->session->config['time_zone'])) );
                    }

                    $reg_original->getRegbyId( $reg->getId() );
                    $reg->setRagKey( $reg_original->getRagKey() );

//$txt = 'Reg =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                    $reg->persistORL();
                }

                // Send success to be displayed
                $response['status'] = 'OK';
                $response['msg'] = $this->lang['RAG_SAVED'];
                $response['action'] = '/'.$this->folder.'/'.$this->lang['RAGS_LINK'];
//$txt = 'Response =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($response, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
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
//$txt = 'Response =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($response, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                echo json_encode($response);
                exit();
            }
        }
        else
        {
//$txt = 'Not submit ========>'.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
            if ( $data['action'] == 'add' )
            {
//$txt = '========== ADD =========='.PHP_EOL; fwrite($this->myfile, $txt);
                $reg->setDateReg( $now->format('d-m-Y H:i:s') );
                $reg->setDateStart( $now->format('d-m-Y') );

                $dt2 = new DateTime('+1 month');
                $reg->setDateEnd( $dt2->format('d-m-Y') );
            }
            else
            {
//$txt = '========== EDIT =========='.PHP_EOL; fwrite($this->myfile, $txt);
                if ( $reg->getRegbyId( $reg->getId() ) )
                {
//$txt = 'REG =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

                    // Field with special treatment
                    $reg->setDateReg( ( $reg->getDateReg() == '' )? NULL : $reg->getDateReg()->format('d-m-Y H:i:s') );
                    $reg->setDateStart( ( $reg->getDateStart() == '' )? NULL : $reg->getDateStart()->format('d-m-Y') );
                    $reg->setDateEnd( ( $reg->getDateEnd() == '' )? NULL : $reg->getDateEnd()->format('d-m-Y') );
                }
                else
                {
                    $_SESSION['alert'] = array(
                                               'type'=>'danger',
                                               'message'=>$this->lang['ERR_RAG_NOT_EXISTS']
                    );
                    header('Location: /'.$this->folder.'/'.$this->lang['RAG_LINK']);
                    exit();
                }
            }
        }

        // account select options list
        require_once(APP_ROOT_PATH . '/src/util/view_selects/account_account.php');

        // billing account select options list
        require_once(APP_ROOT_PATH . '/src/util/view_selects/account_billing_account.php');

        // product setup select options list
        require_once(APP_ROOT_PATH . '/src/util/view_selects/product_rag_setup.php');

        // product select options list
        require_once(APP_ROOT_PATH . '/src/util/view_selects/product_rag_renewal.php');

        // coupon select options list
        require_once(APP_ROOT_PATH.'/src/util/view_selects/coupon.php');

        // agent options
        require_once(APP_ROOT_PATH.'/src/util/view_selects/account_agent.php');

        // Server select options list
        $filter_select = ['active' => '1'];
        $extra_select = 'ORDER BY `name`';
        $data_options_field = 'server_options';
        $server = new serverController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $data[$data_options_field] .= '<option value="0" selected="selected">'.$this->lang['SERVER_SHARED'].'</option>';
        $data[$data_options_field] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';

        $rows = $server->getAll( $filter_select, $extra_select );
        foreach ( $rows as $row )
        {
            $data[$data_options_field] .= '<option value="'.$row['id'].'"'.(( $reg->getserver() == $row['id'])? ' selected="selected" ' : '').'>'.$row['name'].'</option>';
        }

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose($this->myfile);
        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/ragForm.html.twig', array(
            'reg' => $reg->getReg(),
            'filters' => $this->list_filters,
            'pagination' => $this->pagination,
            'data' => $data,
            'cancel' => '/'.$this->folder.'/'.$this->lang['RAGS_LINK'],
        ));
    }
}