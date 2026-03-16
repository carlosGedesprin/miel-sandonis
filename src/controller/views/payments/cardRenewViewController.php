<?php

namespace src\controller\views\payments;

use \src\controller\baseViewController;

use \src\controller\entity\accountPaymentMethodController;

use DateTime;
use DateTimeZone;
use Exception;

class cardRenewViewController extends baseViewController
{
    /**
     *
     * Distributes card renewal to gateway
     *
     * @Route("/payments//renew_card/o6564f05c2o6247f0db0vyz/{payment_method_key}", name="payments_renew_card_o6564f05c2o6247f0db0vyz_payment_method_key")
     *
     */
    public function renewCardAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/cardRenewViewController_'.__FUNCTION__.'.txt', 'a+') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $account_payment_method = new accountPaymentMethodController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        if ( !$account_payment_method->getRegbyKey( $vars['account_payment_method_key'] ) )
        {
//$txt = 'Account Payment Method not found ('.$vars['account_payment_method_key'].')'.PHP_EOL; fwrite($this->myfile, $txt);
            return $this->twig->render('web/'.$this->session->config['website_skin'].'/common/show_message.html.twig', array(
                'section' => $this->lang['PAYMENT_SECTION'],
                'alert_type' => 'danger',
                'title' => $this->lang['WARNING'],
                'message' => $this->lang['ERR_PAYMENT_METHOD_NOT_EXISTS'],
                'redirect_wait' => '5000',
                'redirect' => '/',
            ));
        }
//$txt = 'Account payment method found =========='.$quote->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($account_payment_method->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        switch ( $account_payment_method->getPaymentType() )
        {
            case '1':
                $option_url = '/renew_card/s22e8c1201020e2b3e77/';
                break;
            case '2':
                $option_url = '/renew_card/r53c2o6564f0db0aec4156/';
                break;
        }

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//fclose($this->myfile);
        header( 'Location: /payments'.$option_url.$account_payment_method->getKey() );
        exit;
    }
}
