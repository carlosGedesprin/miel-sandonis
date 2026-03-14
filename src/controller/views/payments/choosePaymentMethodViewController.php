<?php

namespace src\controller\views\payments;

use \src\controller\baseViewController;

use \src\controller\entity\quoteController;
use \src\controller\entity\accountPaymentMethodController;
use \src\controller\entity\accountFundsController;

use DateTime;
use DateTimeZone;
use Exception;

class choosePaymentMethodViewController extends baseViewController
{
    /**
     *
     * Vhoose a payment method
     *
     * @Route("/payments/choose_quote_payment_method/{quote_key}", name="payment_choose_quote_payment_method_quote_key")
     *
     */
    public function choosePaymentMethodAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/payment_choosePaymentMethodController_'.__FUNCTION__.'.txt', 'a+') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $now = new DateTime('now', new DateTimeZone( $this->session->config['time_zone'] ));

        $data = array(
            'quote_key' => $vars['quote_key'],
            'payment_method'  => $this->utils->request_var( 'payment_method', '', 'ALL'),
            'submit' => isset( $_POST['submit'] ) ? true : false,
            'action' => '',
            'url_action' => '/payments/choose_quote_payment_method/'.$vars['quote_key'],
        );

        $error_ajax = array();

//$txt = 'POST =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'DATA =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $quote = new quoteController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $account_payment_method = new accountPaymentMethodController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $accountFund = new accountFundsController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        if ( !$quote->getRegbyQuoteKey( $data['quote_key'] ) )
        {
//$txt = 'Quote not found ('.$data['quote_key'].')'.PHP_EOL; fwrite($this->myfile, $txt);
            return $this->twig->render('web/'.$this->session->config['website_skin'].'/common/show_message.html.twig', array(
                'section' => $this->lang['PAYMENT_SECTION'],
                'alert_type' => 'danger',
                'title' => $this->lang['WARNING'],
                'message' => $this->lang['ERR_PAYMENT_NO_QUOTE'],
                'redirect_wait' => '5000',
                'redirect' => '/',
            ));
        }
//$txt = 'Quote found =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($quote->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        if ( !empty( $quote->getInvoice() ) )
        {
//$txt = 'Quote has been already paid and invoiced'.PHP_EOL; fwrite($this->myfile, $txt);
            return $this->twig->render('web/'.$this->session->config['website_skin'].'/common/show_message.html.twig', array(
                'section' => $this->lang['PAYMENT_SECTION'],
                'alert_type' => 'danger',
                'title' => $this->lang['WARNING'],
                'message' => $this->lang['ERR_QUOTE_ALREADY_PAID'],
                'redirect_wait' => '5000',
                'redirect' => '/',
            ));
        }
//$txt = 'Quote not invoiced'.PHP_EOL; fwrite($this->myfile, $txt);

        if ( $data['submit'] )
        {
            if ( empty($data['payment_method']) )
            {
//$txt = 'No payment method'.PHP_EOL; fwrite($this->myfile, $txt);
                $error_ajax[] = $this->lang['ERR_PAYMENT_METHOD_NEEDED'];
            }

            if ( !sizeof( $error_ajax ) )
            {
//$txt = 'No errors found =========='.PHP_EOL; fwrite($this->myfile, $txt);

                if ( $data['payment_method'] == 'new_card' )
                {
                    $url_continue = '/payments/pay_a_quote/d0fc4acbd986c8f3dafe/'.$quote->getQuoteKey();
//$txt = 'Redirecting to ' . $url_continue . PHP_EOL; fwrite($this->myfile, $txt);
                    header('Location: ' . $url_continue);
                    exit;
                }
                else
                {
                    if ( $account_payment_method->getRegbyKey( $data['payment_method'] ) )
                    {
//$txt = 'Payment method ' . $account_payment_method->getId() . PHP_EOL; fwrite($this->myfile, $txt);
                        $quote->setPaymentMethod( $account_payment_method->getId() );
                        $quote->persist();

                        $url_continue = '/payments/pay_a_quote/a9ce8c1201020e2b3e77/' . $quote->getQuoteKey();
//$txt = 'Redirecting to ' . $url_continue . PHP_EOL; fwrite($this->myfile, $txt);
                        header('Location: ' . $url_continue);
                        exit;
                    }
                    else
                    {
                        return $this->twig->render('web/' . $this->session->config['website_skin'] . '/common/show_message.html.twig', array(
                            'section' => $this->lang['PAYMENT_SECTION'],
                            'alert_type' => 'danger',
                            'title' => $this->lang['WARNING'],
                            'message' => $this->lang['ERR_PAYMENT_METHOD_NEEDED'],
                            'redirect_wait' => '5000',
                            'redirect' => '/payments/choose_quote_payment_method/' . $data['quote_key'],
                        ));
                    }
                }
            }
            else
            {
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
            $account_fund_balance = $accountFund->getBalancebyAccount( $quote->getAccount() );

            $account_payment_methods = $account_payment_method->getAll( ['account' => $quote->getAccount(), 'active' => '1'] );
//$txt = 'Payment methods =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($account_payment_methods, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

            foreach ( $account_payment_methods as $key => $account_payment_method_temp )
            {
                $account_payment_method->getRegbyKey( $account_payment_method_temp['key']) ;
//$txt = 'Payment method '.$account_payment_method->getId().PHP_EOL; fwrite($this->myfile, $txt);

                $date_start = $now->format('Y-m-d');
                $date_end = DateTime::createFromFormat('Y-m-d', $account_payment_method->getExpYear().'-'.$account_payment_method->getExpMonth().'-15', new DateTimeZone($this->session->config['time_zone']));
                $date_end = $date_end->format('Y-m-d');
                $interval = $this->utils->get_interval_in_days( $date_start, $date_end );
//$txt = 'Interval '.$interval.PHP_EOL; fwrite($this->myfile, $txt);

                if ( $interval <= 0 )
                {
                    $account_payment_methods[$key]['expired'] = '1';
                }
            }
        }

        return $this->twig->render('web/'.$this->session->config['website_skin'].'/payment/choose_quote_payment_method.html.twig', array(
            'account_fund_balance' => $account_fund_balance,
            'payment_methods' => $account_payment_methods,
            'data' => $data,
            'cancel' => '/',
        ));
    }
}
