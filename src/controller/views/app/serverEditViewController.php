<?php

namespace src\controller\views\app;

use \src\controller\baseViewController;
use \src\controller\entity\serverController;
use \src\controller\entity\serverServciceController;

use DateTime;
use DateTimeZone;

class serverEditViewController extends baseViewController
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
     * @Route('/app/server/edit/id', name='app_server_edit')
     *
     * @param POST
     *
     * @return object    Twig template
     */
    public function edititemAction( $vars )
    {
        $this->logger->info('==============='.__METHOD__.' Server process '.$vars['id'].' | User '.$this->user.' ===================================================');
$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/serverEditViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']) );

        $form_action = $this->folder.'/server/editor';

        $this->pagination = $this->utils->request_pagination( $this->pagination );
        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        $reg = new serverController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $reg_original = new serverController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $server_service = new serverServciceController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $filter_select = '';
        $extra_select = ' ORDER BY `name`';
        $server_services = $server_service->getAll( $filter_select, $extra_select );

        $reg->setId(( isset( $vars['id'] ) )? $vars['id'] : '0');
        $reg->setAccount( $this->utils->request_var( 'account', '', 'ALL') );
        $reg->setBillingAccount( $this->utils->request_var( 'billing_account', '', 'ALL') );
        $reg->setProductSetup( $this->utils->request_var( 'product_setup', '', 'ALL') );
        $reg->setProductRenewal( $this->utils->request_var( 'product_renewal', '', 'ALL') );
        $reg->setPriceSetup( $this->utils->request_var( 'price_setup', '', 'ALL') );
        $reg->setPriceRenewal( $this->utils->request_var( 'price_renewal', '', 'ALL') );
        $reg->setCoupon( $this->utils->request_var( 'coupon', '', 'ALL') );
        $reg->setName( $this->utils->request_var( 'name', '', 'ALL', true) );
        $reg->setDateReg( $this->utils->request_var( 'date_reg', $now->format('Y-m-d H:i:s'), 'ALL') );
        $reg->setAutoRenew( $this->utils->request_var( 'auto_renew', '1', 'ALL') );
        $reg->setDateStart( $this->utils->request_var( 'date_start', '', 'ALL') );
        $reg->setDateEnd( $this->utils->request_var( 'date_end', '', 'ALL') );
        $reg->setAgent( $this->utils->request_var( 'agent', '', 'ALL') );
        $reg->setServerName( $this->utils->request_var( 'server', '', 'ALL', true) );
        $reg->setIP( $this->utils->request_var( 'ip', '', 'ALL', true) );
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
            'coupon_options' => '',
            'get_product_url' => '/api/get_product',
            'server_services' => $this->utils->request_var( 'server_services', '', 'ALL'),
        );

        $error_ajax = array();

$txt = 'On start function start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
$txt = 'POST =========='.PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'REG =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
$txt = 'Data =========='.PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r($data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
$txt = 'On start function end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        if ( $data['submit'] )
        {
//$txt = 'Submit ========>'.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
            //TODO:Carlos CSRF is not well resolved, when ajax is involved to send the whole form, startup destroys $_SESSION
            // so normal submit will find session and will not log out the user
            /*
            $valid = $this->utils->verifyFormToken($form_action, $data['auth_token'], 5000);
            if (!$valid) {
                return $this->twig->render('app/'.$this->session->config['app_skin'].'/common/show_message.html.twig', array(
                    'section' => $this->lang['SERVER_EDIT'],
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
//$txt = 'account '.$reg->getAccount().PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
                    if ( !empty( $reg->getAccount() ) )
                    {
//$txt = 'Verification name duplicated'.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
                        //serversWithSameName( $name, $type_key, $key, $account )
                        if ( $reg->serversWithSameName( $reg->getName(), 'id', $reg->getId(), $reg->getAccount() ) )
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

//$txt = 'Product to validate => ('.$reg->getProduct().') Date end ('.$reg_original->getDateEnd().')'.PHP_EOL; fwrite($this->myfile, $txt);
            if ( empty( $reg->getProductSetup() ) && empty( $reg->getProductRenewal() ) )
            {
                $error_ajax[] = array(
                    'dom_object' => ['product_setup', 'product_renewal'],
                    'msg' => $this->lang['ERR_PRODUCT_NEEDED']
                );
            }


            if ( $data['action'] == 'add' )
            {
                /*
                if (empty($reg->getServerName()))
                {
                    $error_ajax[] = array(
                        'dom_object' => ['server_name'],
                        'msg' => $this->lang['ERR_SERVER_NAME_NEEDED'],
                    );
                }
                else
                {
                    if (strlen($reg->getServerName()) < 2)
                    {
                        $error_ajax[] = array(
                            'dom_object' => ['server_name'],
                            'msg' => sprintf($this->lang['ERR_SERVER_NAME_SHORT'], '2')
                        );
                    }
                    else if (strlen($reg->getServerName()) > 30)
                    {
                        $error_ajax[] = array(
                            'dom_object' => ['server_name'],
                            'msg' => sprintf($this->lang['ERR_SERVER_NAME_LONG'], '30')
                        );
                    }
                    else
                    {
                        //$txt = 'account '.$reg->getAccount().PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
                        if (!empty($reg->getAccount()))
                        {
                            //$txt = 'Verification name duplicated'.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
                            //serversWithSameName( $name, $type_key, $key, $account )
                            if ($reg->serversWithSameServerName($reg->getServerName(), 'id', $reg->getId(), $reg->getAccount()))
                            {
                                $error_ajax[] = array(
                                    'dom_object' => ['server_name'],
                                    'msg' => $this->lang['ERR_SERVER_NAME_EXISTS'],
                                );
                            }
                        }
                    }
                }
//$txt = 'Name OK'.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
                */
            }

            if ( empty( $data['server_services'] ) )
            {
                $error_ajax[] = array (
                    'dom_object' => [],
                    'msg' => $this->lang['ERR_ACCOUNT_BILLING_ACCOUNT_NEEDED'],
                );
            }
            else
            {
//$txt = 'Services from display =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($data['server_services'], TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                $reg->cleanServices();
                foreach ( $data['server_services'] as $server_service_key => $server_service_value )
                {
                    if ( $server_service_value != 0 ) $reg->setService( $server_service_value );
                }
            }
//$txt = 'Services to Reg =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg->getServices(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

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
                    $reg->setDateStart( ( !empty( $reg->getDateStart() ) )? DateTime::createFromFormat('d-m-Y', $reg->getDateStart(), new DateTimeZone($this->session->config['time_zone'])) : NULL );
                    $reg->setDateEnd( ( !empty( $reg->getDateEnd() ) )? DateTime::createFromFormat('d-m-Y', $reg->getDateEnd(), new DateTimeZone($this->session->config['time_zone'])) : NULL );

$txt = '========== Hetzner start =========='.PHP_EOL; fwrite($this->myfile, $txt);
                    $url_to_call = 'https://api.hetzner.cloud/v1/servers';
                    $server_name = $this->utils->sanitizeDomainName( $reg->getAccount().'-'.$reg->getName() );
$txt = 'Server name =====> '.$server_name.PHP_EOL; fwrite($this->myfile, $txt);
                    $locations = array(
                                            '0' => array(
                                                            'city' => 'Falkenstein',
                                                            'name' => 'fsn1'
                                            ),
                                            '1' => array(
                                                            'city' => 'Nuremberg',
                                                            'name' => 'nbg1'
                                            ),
                                            '2' => array(
                                                            'city' => 'Helsinky',
                                                            'name' => 'hel1'
                                            ),
                    );
                    $location_preferred = 0;
$txt = 'Location =====> '.$locations[$location_preferred]['name'].PHP_EOL; fwrite($this->myfile, $txt);

                    $data_centers = array(
                                            'fsn1' => array(
                                                            'name' => 'fns1-dc14'
                                            ),
                                            'nbg1' => array(
                                                            'name' => 'nbg1-dc3'
                                            ),
                                            'hel1' => array(
                                                            'name' => 'hel1-dc2'
                                            ),
                    );
$txt = 'Data center =====> '.$data_centers[$locations[$location_preferred]['name']]['name'].PHP_EOL; fwrite($this->myfile, $txt);
                    $server_type = array(
                                            'fns1-dc14' => array(
                                                                    '0' => array(
                                                                                'server_type' => 'cax31',
                                                                                'vcpus' => '8',
                                                                                'ram' => '16',
                                                                                'ssd' => '160',
                                                                                'traffic' => '20',
                                                                            ),
                                                                    '1' => array(
                                                                                'server_type' => 'cax41',
                                                                                'vcpus' => '16',
                                                                                'ram' => '32',
                                                                                'ssd' => '320',
                                                                                'traffic' => '20',
                                                                            ),
                                            ),
                                            'nbg1-dc3' => array(
                                                                    '0' => array(
                                                                        'server_type' => 'cax31',
                                                                        'vcpus' => '8',
                                                                        'ram' => '16',
                                                                        'ssd' => '160',
                                                                        'traffic' => '20',
                                                                    ),
                                                                    '1' => array(
                                                                        'server_type' => 'cax41',
                                                                        'vcpus' => '16',
                                                                        'ram' => '32',
                                                                        'ssd' => '320',
                                                                        'traffic' => '20',
                                                                    ),
                                            ),
                                            'hel1-dc2' => array(
                                                                    '0' => array(
                                                                        'server_type' => 'cax31',
                                                                        'vcpus' => '8',
                                                                        'ram' => '16',
                                                                        'ssd' => '160',
                                                                        'traffic' => '20',
                                                                    ),
                                                                    '1' => array(
                                                                        'server_type' => 'cax41',
                                                                        'vcpus' => '16',
                                                                        'ram' => '32',
                                                                        'ssd' => '320',
                                                                        'traffic' => '20',
                                                                    ),
                                            ),
                        );
$txt = 'Server type =====> '.$server_type[$data_centers[$locations[$location_preferred]['name']]['name']][0]['server_type'].PHP_EOL; fwrite($this->myfile, $txt);
                    $snapshot_template = '337946508';
                    $placement_group = 1; // Is an example
                    $volumes = 123; // Is an example
                    $networks = 456; // Is an example
                    $firewalls = array(
                                        array(
                                                "firewall" => 10259000,
                                            ),
                                        );
                    $cloud_config = "#cloud-config\nruncmd:\n- [touch, /root/cloud-init-worked]\n";
                    $cloud_config_key = array(
                                                '%user_name%',
                    );
                    $cloud_config_value = array(
                                                'pepito',
                    );
                    $cloud_config = str_replace( $cloud_config_key, $cloud_config_value );
                    $data = array(
                                      "name" => $server_name,
                                      "location" => $locations[$location_preferred]['name'],
                                      //"datacenter" => $data_centers[$locations[$location_preferred]['name']]['name'], (datacenter OR location, we prefer location)
                                      "server_type" => $server_type[$data_centers[$locations[$location_preferred]['name']]['name']][0]['server_type'],
                                      "start_after_create" => true,
                                      "image" => $snapshot_template,
                                      //"placement_group" => $placement_group,
                                      "ssh_keys" => ["webmaster@altiraautomations.com"],
                                      //"volumes" => [$volumes],
                                      //"networks" => [$networks],
                                      "firewalls" => $firewalls,
                                      "user_data" => $cloud_config,
                                      /*
                                      "labels" => array(
                                                         "environment" => "prod",
                                                         "example.com/my" => "label",
                                                         "just-a-key" => ""
                                                        ),
                                      */
                                      //"automount" => false,
                                      "public_net" => array(
                                                             "enable_ipv4" => true,
                                                             "enable_ipv6" => false,
                                                             //"ipv4" => null,
                                                             //"ipv6" => null
                                      )
                    );
$txt = 'Request data =====>'.PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r($data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

                    $response = $this->utils->send_to_api( $url_to_call, $data, $_ENV['hetzner_api_token'] );
$txt = 'Api response =====>'.PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r($response, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                    if ( !isset( $response['error'] ) )
                    {
                        $reg->setServerName( $server_name );
                        $reg->setIP( $response['server']['public_net']['ipv4']['ip'] );
                        $reg->setServerId( $response['server']['id'] );
                        $reg->setBulkInfo( $response );
                        $reg->setActive( '2' ); //0-un active 1-active 2-initializing

                        $reg->persistORL();

                        $reg->setServerKey( md5( $reg->getId().$reg->getName() ) );
                        $reg->persist();
                    }
                    else
                    {
$txt = 'Api response error =====> '.$response['error']['message'].PHP_EOL; fwrite($this->myfile, $txt);

                        $error_ajax[] = array(
                            'dom_object' => [],
                            'msg' => $this->lang['ERRORS_FOUND'].': '.$response['error']['message'],
                        );
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
$txt = '========== Hetzner end =========='.PHP_EOL; fwrite($this->myfile, $txt);

$txt = 'Reg =========='.PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r($reg->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                }
                else
                {
//$txt = '========== EDIT =========='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Reg =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
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
                    $reg->setServerKey( $reg_original->getServerKey() );
                    $reg->setServerName( $reg_original->getServerName() );

//$txt = 'Reg =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                    $reg->persistORL();
                }
/*
                $reg->cleanServices();
                foreach ( $data['server_services'] as $server_service_key => $server_service_value )
                {
//$txt = 'Service to treat ======= '.$server_service_key.' => '.$server_service_value.PHP_EOL; fwrite($this->myfile, $txt);
                    if ( $server_service_value != '0' )
                    {
                        $reg->setService( $server_service_value );
                    }
                }
                $reg->persist();
*/
                // Send success to be displayed
                $response['status'] = 'OK';
                $response['msg'] = $this->lang['SERVER_SAVED'];
                $response['action'] = '/'.$this->folder.'/'.$this->lang['SERVERS_LINK'];
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

//$txt = 'Server services available ====> '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($server_services, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'This server services ====> '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg->getServices(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                    foreach ( $server_services as $key => $value )
                    {
//$txt = 'Server services to search ====> '.$value['id'].PHP_EOL; fwrite($this->myfile, $txt);
                        //if ( array_search( $value['id'], $reg->getTags() ) )
                        if ( ( $i = array_search( $value['id'], $reg->getServices() ) ) !== FALSE )
                        {
//$txt = 'Server services is in field ====> '.$value['id'].PHP_EOL; fwrite($this->myfile, $txt);
                            $server_services[$key]['selected'] = '1';
                        }
                    }
//$txt = 'Server servicess ====> '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($server_services, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                }
                else
                {
                    $_SESSION['alert'] = array(
                                               'type'=>'danger',
                                               'message'=>$this->lang['ERR_SERVER_NOT_EXISTS']
                    );
                    header('Location: /'.$this->folder.'/'.$this->lang['SERVERS_LINK']);
                    exit();
                }
            }
        }

        // account select options list
        require_once(APP_ROOT_PATH . '/src/util/view_selects/account_account.php');

        // billing account select options list
        require_once(APP_ROOT_PATH . '/src/util/view_selects/account_billing_account.php');

        // product setup select options list
        require_once(APP_ROOT_PATH . '/src/util/view_selects/product_server_setup.php');

        // product select options list
        require_once(APP_ROOT_PATH . '/src/util/view_selects/product_server_renewal.php');

        // coupon select options list
        require_once(APP_ROOT_PATH.'/src/util/view_selects/coupon.php');

        // agent options
        require_once(APP_ROOT_PATH.'/src/util/view_selects/account_agent.php');

$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
fclose($this->myfile);
        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/serverForm.html.twig', array(
            'reg' => $reg->getReg(),
            'server_services' => $server_services,
            'filters' => $this->list_filters,
            'pagination' => $this->pagination,
            'data' => $data,
            'cancel' => '/'.$this->folder.'/'.$this->lang['SERVERS_LINK'],
        ));
    }
}