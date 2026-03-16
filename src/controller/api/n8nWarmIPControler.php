<?php

namespace src\controller\api;

use \src\controller\baseController;

use \src\controller\entity\N8NWarmIPAccount;
use \src\controller\entity\N8NWarmIPEmailController;

use DateTime;
use DateTimeZone;

class n8nWarmIPControler extends baseController
{
    /**
     * @Route('api/n8n/get_warm_ip_accounts_to_process', name='api_n8n_get_warm_ip_accounts_to_process')
     */
    public function n8nGetAccountsAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH . '/var/logs/api_n8n_Warm_IPControler_' . __FUNCTION__ . '.txt', 'w') or die('Unable to open file!');
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
//fwrite($this->myfile, print_r($api_request, TRUE));
            $data_received = $api_request['data'];
//$txt = 'Data received =======>'.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($data_received, TRUE));

            $account = new N8NWarmIPAccount( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

            $account_email = new N8NWarmIPEmailController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

            $error = false;

            if ( !$error )
            {
                $filter_select = array(
                                        'active' => 1
                );
                $extra_select = ' ORDER BY `name`';
                $accounts = $account->getAll( $filter_select, $extra_select);

                foreach ( $accounts as $account_key => $account_value )
                {
                    $account->getRegbyId( $account_value['id'] );

                    $account_emails = $account_email->getAccountEmailsByEmailToWarm( $account->getId(), $data_received['warm_email'] );

                    $accounts[$account_key]['emails'] = $account_emails;
//$txt = 'Accounts =======>'.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($accounts, TRUE));
                }

                $response = array(
                                    'status' => 'OK',
                                    'active_accounts' => $accounts
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
     * @Route('api/n8n/get_warm_ip_account_by_id', name='api_n8n_get_warm_ip_account_by_id')
     */
    public function n8nGetAccountByIdAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH . '/var/logs/api_n8n_WarmIPControler__' . __FUNCTION__ . '.txt', 'w') or die('Unable to open file!');
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

            $account = new N8NWarmIPAccount( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

            $account_email = new N8NWarmIPEmailController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

            $error = false;

            if ( !$error )
            {
                $account->getRegbyId( $data_received['id'] );

                $account_emails = $account_email->getAccountEmails( $account->getId() );

                $account['emails'] = $account_emails;
//$txt = 'Accounts =======>'.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($accounts, TRUE));

                $response = array(
                                    'status' => 'OK',
                                    'account' => $account
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
     * @Route('api/n8n/get_warm_ip_account_by_email', name='api_n8n_get_warm_ip_account_by_email')
     */
    public function n8nGetAccountByEmailAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH . '/var/logs/api_n8n_WarmIPControler__' . __FUNCTION__ . '.txt', 'a+') or die('Unable to open file!');
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

//$txt = 'Data received raw =======>'.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($api_request, TRUE));
            $data_received = $api_request['data'];
//$txt = 'Data received =======>'.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($data_received, TRUE));

            $account = new N8NWarmIPAccount( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

            $account_email = new N8NWarmIPEmailController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

            $error = false;

            if ( !isset( $data_received['email'] ) || empty( $data_received['email'] ) )
            {
                $error = true;
                $msg = 'Email not received';
//$txt = 'Email received is NULL or empty'.PHP_EOL;fwrite($this->myfile, $txt);
            }

            if ( !isset( $data_received['warm_email'] ) || empty( $data_received['warm_email'] ) )
            {
                $error = true;
                $msg = 'Warm email not received';
//$txt = 'Warm email received is NULL or empty'.PHP_EOL;fwrite($this->myfile, $txt);
            }

            if ( !$error )
            {
                $account->getRegbyEmail( $data_received['email'] );

                $account_temp = $account->getReg();

                $account_emails = $account_email->getAccountEmailsByEmailToWarm( $account->getId(), $data_received['warm_email'] );

                $account_temp['emails'] = $account_emails;
//$txt = 'Account =======>'.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($account_temp, TRUE));

                $response = array(
                                    'status' => 'OK',
                                    'account' => $account_temp
                );
            }
            else
            {

                $response = array(
                                    'status' => 'KO',
                                    'account' => $msg
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
     * * @Route('api/n8n/set_warm_ip_account_email', name='api_n8n_set_warm_ip_account_email')
     */
    public function n8nSetAccountEmailAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH . '/var/logs/api_n8n_WarmIPControler_' . __FUNCTION__ . '.txt', 'a+') or die('Unable to open file!');
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

            $account = new N8NWarmIPAccount( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

            $account_email = new N8NWarmIPEmailController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

            $error = false;

            if ( !isset( $data_received['account_id'] ) || empty( $data_received['account_id'] ) )
            {
                $error = true;
                $msg = 'Account id not received';
//$txt = 'Account id received is NULL or empty'.PHP_EOL;fwrite($this->myfile, $txt);
            }
            else
            {
                if ( !$account->getRegbyId( $data_received['account_id'] ) )
                {
//$txt = 'Account NOT found =======> '.$data_received['account_id'].PHP_EOL;fwrite($this->myfile, $txt);
                    $error = true;
                    $msg = 'Account not found';
                }
            }

            if ( !isset( $data_received['warm_email'] ) || empty( $data_received['warm_email'] ) )
            {
                $error = true;
                $msg = 'Warm email not received';
//$txt = 'Warm email received is NULL or empty'.PHP_EOL;fwrite($this->myfile, $txt);
            }

            if ( !$error )
            {
//$txt = 'Account found =======> '.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($account->getReg(), TRUE));

                $account_email->setAccount( $account->getId() );
                $account_email->setDateSent( $now );
                $account_email->setWarmEmail( $data_received['warm_email'] );
                $account_email->setSubject( $data_received['subject'] );
                $account_email->setBody( $data_received['body'] );

                $account_email->persist();

                $response = array(
                                    'status' => 'OK',
                                    'result' => $account_email->getReg()
                );
            }
            else
            {
                $response = array(
                    'status' => 'KO',
                    'result' => $msg
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
