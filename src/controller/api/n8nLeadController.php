<?php

namespace src\controller\api;

use \src\controller\baseController;

use src\controller\entity\N8NleadController as n8n_leadController;
use src\controller\entity\N8NleadEmailController;

use DateTime;
use DateTimeZone;

class n8nLeadController extends baseController
{
    /**
     * @Route('api/n8n/get_leads', name='api_n8n_get_leads')
     */
    public function n8nGetLeadsAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH . '/var/logs/api_n8n_controller_' . __FUNCTION__ . '.txt', 'w') or die('Unable to open file!');
//$txt = '====================== ' . __METHOD__ . ' start ======================================' . PHP_EOL; fwrite($this->myfile, $txt);

        $api_request = $this->utils->checkAPIRequest();
//$txt = 'Api request =======>'.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($api_request, TRUE));

        if ( $api_request['status'] == 'KO' )
        {
//$txt = 'Error '.$api_request['data']['error_code'].' '.$api_request['data']['error_des'].' in Request ========>'.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($api_request, TRUE));
            $response = array(
                'status' => 'KO',
                'error_message' => 'API Request Error Code '.$api_request['data']['error_code'],
            );
        }
        else
        {
//$txt = 'No Errors'.PHP_EOL;fwrite($this->myfile, $txt);

//$txt = 'Data received =======>'.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($$api_request, TRUE));
            $data_received = $api_request['data']['data'];
//$txt = 'Data received =======>'.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($data_received, TRUE));

            $lead = new n8n_leadController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

            $lead_email = new n8NleadEmailController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

            $error = false;

            if ( !$error )
            {
                $filter_select = array(
                                        'active' => 1
                );
                $extra_select = ' ORDER BY `name`';
                $leads = $lead->getAll( $filter_select, $extra_select);

                foreach ( $leads as $lead_key => $lead_value )
                {
                    $lead->getRegbyId( $lead_value['id'] );

                    $lead_emails = $lead_email->getLeadEmails( $lead->getId() );

                    $leads[$lead_key]['emails'] = $lead_emails;
//$txt = 'Leads =======>'.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($leads, TRUE));
                }

                $response = array(
                                    'status' => 'OK',
                                    'active_leads' => $leads
                );
            }
        }
//$txt = 'Response =======>'.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($response, TRUE));
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose($this->myfile);
        header('Content-type: application/json');
        echo json_encode( $response );
    }
    /**
     * * @Route('api/n8n/set_lead', name='api_n8n_set_lead')
     */
    public function n8nSetLeadAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH . '/var/logs/api_n8n_controller_' . __FUNCTION__ . '.txt', 'a+') or die('Unable to open file!');
//$txt = '====================== ' . __METHOD__ . ' start ======================================' . PHP_EOL; fwrite($this->myfile, $txt);

        $api_request = $this->utils->checkAPIRequest();
//$txt = 'Api request =======>'.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($api_request, TRUE));

        if ( $api_request['status'] == 'KO' )
        {
//$txt = 'Error '.$api_request['data']['error_code'].' '.$api_request['data']['error_des'].' in Request ========>'.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($api_request, TRUE));
            $response = array(
                'status' => 'KO',
                'result' => 'API Request Error Code '.$api_request['data']['error_code'],
            );
        }
        else
        {
//$txt = 'No Errors'.PHP_EOL;fwrite($this->myfile, $txt);

            //$data_received = $api_request['data']['data']['data'];
            $data_received = $api_request['data']['data'];
//$txt = 'Data received =======>'.PHP_EOL;fwrite($this->myfile, $txt); fwrite($this->myfile, print_r($data_received, TRUE));

            $lead = new n8NleadController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

            $error = false;

            $response = array(
                                'status' => 'KO',
                                'result' => ''
            );

            if ( !$error )
            {
                $lead->createLead( $data_received['name'], $data_received['email'], $data_received['phone'] );

                $lead_bulk_info = $lead->getBulkInfo();
                $lead_bulk_info .= $data_received;

                $lead->persist();

                $response = array(
                                    'status' => 'OK',
                                    'result' => $lead->getReg()
                );
            }
        }
//$txt = 'Response =======>'.PHP_EOL;fwrite($this->myfile, $txt); fwrite($this->myfile, print_r($response, TRUE));
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose($this->myfile);
        header('Content-type: application/json');
        echo json_encode( $response );
    }
    /**
     * @Route('api/n8n/get_lead_by_id', name='api_n8n_get_lead_by_id')
     */
    public function n8nGetLeadByIdAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH . '/var/logs/api_n8n_controller_' . __FUNCTION__ . '.txt', 'w') or die('Unable to open file!');
//$txt = '====================== ' . __METHOD__ . ' start ======================================' . PHP_EOL; fwrite($this->myfile, $txt);

        $api_request = $this->utils->checkAPIRequest();
//$txt = 'Api request =======>'.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($api_request, TRUE));

        if ( $api_request['status'] == 'KO' )
        {
//$txt = 'Error '.$api_request['data']['error_code'].' '.$api_request['data']['error_des'].' in Request ========>'.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($api_request, TRUE));
            $response = array(
                'status' => 'KO',
                'error_message' => 'API Request Error Code '.$api_request['data']['error_code'],
            );
        }
        else
        {
//$txt = 'No Errors'.PHP_EOL;fwrite($this->myfile, $txt);

            //$data_received = $api_request['data']['data']['data'];
            $data_received = $api_request['data']['data'];
//$txt = 'Data received =======>'.PHP_EOL;fwrite($this->myfile, $txt); fwrite($this->myfile, print_r($data_received, TRUE));

            $lead = new n8NleadController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

            $lead_email = new n8NleadEmailController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

            $error = false;

            if ( !$error )
            {
                $lead->getRegbyId( $data_received['id'] );

                $lead_emails = $lead_email->getLeadEmails( $lead->getId() );

                $lead['emails'] = $lead_emails;
//$txt = 'Leads =======>'.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($leads, TRUE));

                $response = array(
                                    'status' => 'OK',
                                    'lead' => $lead
                );
            }
        }
//$txt = 'Response =======>'.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($response, TRUE));
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose($this->myfile);
        header('Content-type: application/json');
        echo json_encode( $response );
    }
    /**
     * @Route('api/n8n/get_lead_by_email', name='api_n8n_get_lead_by_email')
     */
    public function n8nGetLeadByEmailAction( $vars )
    {
$this->myfile = fopen(APP_ROOT_PATH . '/var/logs/api_n8n_controller_' . __FUNCTION__ . '.txt', 'w') or die('Unable to open file!');
$txt = '====================== ' . __METHOD__ . ' start ======================================' . PHP_EOL; fwrite($this->myfile, $txt);

        $api_request = $this->utils->checkAPIRequest();
//$txt = 'Api request =======>'.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($api_request, TRUE));

        if ( $api_request['status'] == 'KO' )
        {
//$txt = 'Error '.$api_request['data']['error_code'].' '.$api_request['data']['error_des'].' in Request ========>'.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($api_request, TRUE));
            $response = array(
                'status' => 'KO',
                'error_message' => 'API Request Error Code '.$api_request['data']['error_code'],
            );
        }
        else
        {
//$txt = 'No Errors'.PHP_EOL;fwrite($this->myfile, $txt);

$txt = 'Data received raw =======>'.PHP_EOL;fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r($api_request, TRUE));
            $data_received = $api_request['data'];
//$txt = 'Data received =======>'.PHP_EOL;fwrite($this->myfile, $txt); fwrite($this->myfile, print_r($data_received, TRUE));

            $lead = new n8NleadController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

            $lead_email = new n8NleadEmailController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

            $error = false;

            if ( !$error )
            {
                $lead->getRegbyEmail( $data_received['email'] );

                $lead_temp = $lead->getReg();

                $lead_emails = $lead_email->getLeadEmails( $lead->getId() );

                $lead_temp['emails'] = $lead_emails;
$txt = 'Lead =======>'.PHP_EOL;fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r($lead_temp, TRUE));

                $response = array(
                                    'status' => 'OK',
                                    'lead' => $lead_temp
                );
            }
        }
$txt = 'Response =======>'.PHP_EOL;fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r($response, TRUE));
$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
fclose($this->myfile);
        header('Content-type: application/json');
        echo json_encode( $response );
    }
    /**
     * * @Route('api/n8n/set_lead_email', name='api_n8n_set_lead_email')
     */
    public function n8nSetLeadEmailAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH . '/var/logs/api_n8n_controller_' . __FUNCTION__ . '.txt', 'a+') or die('Unable to open file!');
//$txt = '====================== ' . __METHOD__ . ' start ======================================' . PHP_EOL; fwrite($this->myfile, $txt);

        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']));

        $api_request = $this->utils->checkAPIRequest();
//$txt = 'Api request =======>'.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($api_request, TRUE));

        if ( $api_request['status'] == 'KO' )
        {
//$txt = 'Error '.$api_request['data']['error_code'].' '.$api_request['data']['error_des'].' in Request ========>'.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($api_request, TRUE));
            $response = array(
                'status' => 'KO',
                'result' => 'API Request Error Code '.$api_request['data']['error_code'],
            );
        }
        else
        {
//$txt = 'No Errors'.PHP_EOL;fwrite($this->myfile, $txt);

//$txt = 'Data received raw =======>'.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($api_request, TRUE));
            //$data_received = $api_request['data']['data'];
            $data_received = $api_request['data'];
//$txt = 'Data received =======>'.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($data_received, TRUE));

            $lead = new n8NleadController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

            $lead_email = new n8NleadEmailController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

            $error = false;

            $response = array(
                                'status' => 'KO',
                                'result' => ''
            );

            if ( !$error )
            {

                $lead_email->setLead( $data_received['lead_id'] );
                $lead_email->setDateSent( $now );
                $lead_email->setSubject( $data_received['subject'] );
                $lead_email->setBody( $data_received['body'] );

                $lead_email->persist();

                $response = array(
                                    'status' => 'OK',
                                    'result' => $lead_email->getReg()
                );
            }
        }
//$txt = 'Response =======>'.PHP_EOL;fwrite($this->myfile, $txt); fwrite($this->myfile, print_r($response, TRUE));
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose($this->myfile);
        header('Content-type: application/json');
        echo json_encode( $response );
    }
}
