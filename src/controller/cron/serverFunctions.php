<?php

namespace src\controller\cron;

use \src\controller\entity\serverController;

use \src\controller\entity\langTextController;
use \src\controller\entity\mailQueueController;

use DateTime;
use DateTimeZone;
use DateInterval;

class serverFunctions
{
    private $env;
    private $logger;
    private $logger_err;
    private $startup;
    private $db;
    private $utils;
    private $session;
    private $lang;
    private $twig;

    private $myfile;

    public function __construct( $env, $logger, $logger_err, $startup, $db, $utils, $session, $lang, $twig )
    {
        $this->env = $env;
        $this->logger = $logger;
        $this->logger_err = $logger_err;
        $this->startup = $startup;
        $this->db = $db;
        $this->utils = $utils;
        $this->session = $session;
        $this->lang = $lang;
        $this->twig = $twig;

$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/cron_serverFunctions_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'POST =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']) );

        $result = true;
        $this->serversProcess();
        //$this->seversMailingProcess();

$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
fclose($this->myfile);
        return $result;
    }
    /**
     *
     * Checks if server is available in hetzner
     *
     */
    private function serversProcess()
    {
$txt = PHP_EOL.'====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
            
        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']) );

        $server = new serverController( array( 'env' => $_ENV, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils,'session' => $this->session,'lang' => $this->lang, 'twig' => $this->twig ) );

        $filter_select = '';
        $extra_select = ' WHERE active not in (0,1)';
        $servers = $server->getAll( $filter_select, $extra_select);
$txt = 'Initializing servers ========> '.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r($servers, TRUE));

        foreach ( $servers as $server_temp )
        {
            $server->getRegbyId( $server_temp['id'] );
//$txt = 'Server '.print_r($server->getReg(), true).PHP_EOL; fwrite($this->myfile, $txt);

            switch ( $server->getServerStatus() )
            {
                case '2':
                    $server->checkServerIsRunning();
                    break;
                case '3':
                    // Ver que hacer con la contraseña del root (la tenemos en el config hetzner_root_password)
                    $server->allowRootConnect();
                    break;
                case '4':
                    // registrar DNS nombre del subdominio 'nombre-servidor'.altiraautomations.com a la IP
                    if ( $server->checkDNSrecord() )
                    {
                        // (ver como comprobar que esté replicada -> $this->>utils->validate_domain_dns(domain-name))
                        $server->checkDNSPropagation();
                    }
                    else
                    {
                        $server->setDNSRecord();
                    }
                    break;
                case '5':
                    // Solictar certificado y el cliente accede a los servicios-dockers (n8n, qudarnt admin...) por puerto
                    $server->requestCertificate();
                    break;
                case '6':
                    // usuario Benito (Wheel) está en la imagen y en config (hetzner_benito_username, hetzner_benito_password)
                    // crear usuario ssh para cliente
                    $server->createCustomerUser();
                    break;
                case '7':
                    // crear usuario ftp común (nuestro) en config (hetzner_ftp_username, hetzner_ftp_password)
                    // Crear carpeta de documentos RAG (/var/www/rag/)
                    break;
            }
        }
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
    }

    private function seversMailingProcess()
    {
//$txt = PHP_EOL.'====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        
        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']) );

        $commission = new commissionController( array( 'env' => $_ENV, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils,'session' => $this->session,'lang' => $this->lang, 'twig' => $this->twig ) );

        $settlement = new settlementController( array( 'env' => $_ENV, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils,'session' => $this->session,'lang' => $this->lang, 'twig' => $this->twig ) );

        $account = new accountController( array( 'env' => $_ENV, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils,'session' => $this->session,'lang' => $this->lang, 'twig' => $this->twig ) );

        $user = new userController( array( 'env' => $_ENV, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils,'session' => $this->session,'lang' => $this->lang, 'twig' => $this->twig ) );

        $vat_type = new vatTypeController( array( 'env' => $_ENV, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils,'session' => $this->session,'lang' => $this->lang, 'twig' => $this->twig ) );

        $mailQueue = new mailQueueController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

//$txt = 'Settlements '.print_r( $this->settlements_treated, true ).PHP_EOL;fwrite($this->myfile, $txt);
        foreach ( $this->settlements_treated as $settlement_to_treat )
        {
//$txt = 'Settlement to treat '.print_r( $settlement_to_treat, true ).PHP_EOL;fwrite($this->myfile, $txt);

            $settlement->getRegbyId( $settlement_to_treat['id'] );

            $account->getRegbyId( $settlement->getAccount() );

            $user->getRegbyId( $account->getMainUser() );

            $doc_data = array(
                'id' => $settlement->getId(),
                'page_orientation' => 'P',
                'unit' => 'mm',
                'page_format' => 'A4',
                'template' => 'LC',
                'web_name' => $this->session->config['web_name'],
                'assign_vars' => array()
            );
            
            $doc_data['assign_vars']['settlement_label'] = langTextController::getLangText( $this->utils, $user->getLocale(), 'SETTLEMENT_LABEL' );
            $doc_data['assign_vars']['settlement_id'] = $settlement->getId();

            if ( !empty( $this->session->config['company_region'] ) )
            {
                $api_data = array(
                    'api_key' => $this->session->config['locations_api_key'],
                    'region_code' => $this->session->config['company_region'],
                    'lang_2a' => $this->session->config['web_locale'],
                );
                $route = '/get_region_name_by_region_code';
                $api_response = $this->utils->get_from_locations_api( $route, $api_data );
//$txt = 'Api Response ============='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($api_response, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

                if ( $api_response['status'] == 'OK' )
                {
                    $account_region = $api_response['msg']['name'];
                }
                else
                {
                    $account_region = $api_response['message'];
                }
            }
            if ( !empty( $this->session->config['company_city'] ) )
            {
                $api_data = array(
                    'api_key' => $this->session->config['locations_api_key'],
                    'city_code' => $this->session->config['company_city'],
                    'lang_2a' => $account->getLocale(),
                );
                $route = '/get_city_name_by_city_code';
                $api_response = $this->utils->get_from_locations_api( $route, $api_data );
//$txt = 'Api Response ============='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($api_response, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

                if ( $api_response['status'] == 'OK' )
                {
                    $account_city = $api_response['msg']['name'];
                }
                else
                {
                    $account_city = $api_response['message'];
                }
            }

            $company_addresses = array(
                $this->session->config['company_address_5'],
                $this->session->config['company_address_4'],
                $this->session->config['company_address_3'],
                $this->session->config['company_address_2'],
                $this->session->config['company_address_1']
            );

            $address = '';

            foreach ($company_addresses as $company_address) {
                if ( !empty($company_address) ) {
                    $address = $company_address;
                }
            }
            
            $doc_data['assign_vars']['account_name'] = $this->session->config['company_name'];
            $doc_data['assign_vars']['account_address'] = $address;
            $doc_data['assign_vars']['account_region'] = $account_region;
            $doc_data['assign_vars']['account_city'] = $this->session->config['company_postcode'].'  '.$account_city;
            $doc_data['assign_vars']['account_nif'] = $this->session->config['company_vat'];
            if ( !empty( $doc_data['assign_vars']['account_nif'] ) ) 
            {
                $doc_data['assign_vars']['nif_label'] = langTextController::getLangText( $this->utils, $user->getLocale(), 'SETTLEMENT_NIF_LABEL' );
            }
            $doc_data['assign_vars']['not_valid'] = '';

            $doc_data['assign_vars']['totals_label'] = langTextController::getLangText( $this->utils, $user->getLocale(), 'SETTLEMENT_TOTALS_LABEL' );

            $commisssions_of_settlement = $commission->getAllCommissionsOfSettlement( $settlement->getId() );

            $comms_desc = '';
            $comms_amount = '';
            $comms_percent = '';
            $comms_total = '';

            foreach ( $commisssions_of_settlement as $commisssion_of_settlement )
            {
                $commission->getRegbyId( $commisssion_of_settlement['id'] );

                $comms_desc .= $commission->getDescription()."\n";
                $comms_amount .= number_format( floatval( $commission->getInvoiceNet() ) / 100, 2, ',', '.' ).'€'."\n";
                $comms_percent .= number_format( floatval( $commission->getCommissionPercent() ) / 100, 2, ',', '.' ).'%'."\n";
                $comms_total .= number_format( floatval( $commission->getTotal() ) / 100, 2, ',', '.' ).'€'."\n";
            }
            
            $doc_data['assign_vars']['description_label'] = langTextController::getLangText( $this->utils, $user->getLocale(), 'SETTLEMENT_DESCRIPTION_LABEL' );
            $doc_data['assign_vars']['comms_desc'] = $comms_desc;
            
            $doc_data['assign_vars']['amount_label'] = langTextController::getLangText( $this->utils, $user->getLocale(), 'SETTLEMENT_AMOUNT_LABEL' );
            $doc_data['assign_vars']['comms_amount'] = $comms_amount;
            
            $doc_data['assign_vars']['percentage_label'] = langTextController::getLangText( $this->utils, $user->getLocale(), 'SETTLEMENT_PERCENTAGE_LABEL' );
            $doc_data['assign_vars']['comms_percent'] = $comms_percent;
            
            $doc_data['assign_vars']['total_commission_label'] = langTextController::getLangText( $this->utils, $user->getLocale(), 'SETTLEMENT_TOTAL_COMMISSION_LABEL' );
            $doc_data['assign_vars']['comms_total'] = $comms_total;
            
            $settlement_date = $settlement->getDate()->format('d-m-Y');

            $doc_data['assign_vars']['date_label'] = langTextController::getLangText( $this->utils, $user->getLocale(), 'SETTLEMENT_DATE_LABEL' );
            $doc_data['assign_vars']['settlement_date'] = $settlement_date;

            $doc_data['assign_vars']['total_no_vat_label'] = langTextController::getLangText( $this->utils, $user->getLocale(), 'SETTLEMENT_TOTAL_NO_VAT_LABEL' );
            $doc_data['assign_vars']['total_no_vat'] = number_format( floatval( $settlement->getNet() ) / 100, 2, ',', '.' ).'€';
            
            $doc_data['assign_vars']['vat_label'] = langTextController::getLangText( $this->utils, $user->getLocale(), 'SETTLEMENT_VAT_LABEL' );
            $vat_type->getRegbyId( $account->getVatType() );
            $doc_data['assign_vars']['vat'] = number_format( $vat_type->getPercent() / 100, 1, ',', '.' ).'%';
            $doc_data['assign_vars']['sum_vat'] = number_format( floatval( $settlement->getVatAmount() ) / 100, 2, ',', '.' ).'€';
            
            $doc_data['assign_vars']['total_vat_label'] = langTextController::getLangText( $this->utils, $user->getLocale(), 'SETTLEMENT_TOTAL_VAT_LABEL' );
            $doc_data['assign_vars']['total_vat'] = number_format( floatval( $settlement->getTotalToPay() ) / 100, 2, ',', '.' ).'€';
            

            $tax_data_text = langTextController::getLangText( $this->utils, $user->getLocale(), 'SETTLEMENT_TAX_DATA' );

            $doc_data['assign_vars']['tax_data'] = sprintf( $tax_data_text, 
                $account->getCompany(),
                $account->getVat(),
                $account->getAddress().' '.$account->getPostCode(),
                $account->getPhone(),
                $account->getNotificationsEmail()
            );

            $doc_data['assign_vars'] = serialize( $doc_data['assign_vars'] );

//$txt = 'Documento a enviar '.print_r( $doc_data, true ).PHP_EOL;fwrite($this->myfile, $txt);

            $pdf = $this->utils->printDoc( $doc_data, 'F', 'temp' );

//$txt = 'Direccion PDF '.$pdf.' '.PHP_EOL; fwrite($this->myfile, $txt);
            $mailQueue->setToName( $account->getName() );
            $mailQueue->setToAddress( $account->getNotificationsEmail() );
            $mailQueue->setLocale( $user->getLocale() );

            $mailQueue->addAttached( 'settlement', $pdf );

            $mailQueue->setTemplate('settlement_commissions_pdf');
            $mailQueue->setProcess(__METHOD__ );
            $subject = langTextController::getLangText( $this->utils, $user->getLocale(), 'MAIL_SETTLEMENT_PDF_SUBJECT' );
            $subject = str_replace( '%web_name%' , $this->session->config['web_name'], $subject );
            $mailQueue->setSubject( $subject );
            $pre_header = langTextController::getLangText( $this->utils, $user->getLocale(), 'MAIL_SETTLEMENT_PDF_PREHEADER' );
            $pre_header = str_replace( '%web_name%' , $this->session->config['web_name'], $pre_header );
            $mailQueue->setPreheader( $pre_header );
            $mailQueue->persist();
//$txt = 'Mail creado '.print_r($mailQueue->getReg(), true).' '.PHP_EOL; fwrite($this->myfile, $txt);

            $mailQueue->setId('');

        }
//fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose($this->myfile);
    }
}