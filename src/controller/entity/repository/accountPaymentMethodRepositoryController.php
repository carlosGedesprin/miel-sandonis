<?php

namespace src\controller\entity\repository;


use DateTime;
use DateTimeZone;

/**
 * Trait accountPaymentMethod
 * @package entity
 */
trait accountPaymentMethodRepositoryController
{
    /**
     *
     * Get all payment methods from specific date
     */
    public function getAllFromDate( $date )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Date ========>'.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($date, TRUE));
//$txt = 'Date Month '.$date->format('n').' Year '.$date->format('Y').PHP_EOL;fwrite($this->myfile, $txt);

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $this->getAll( ['exp_month' => $date->format('n'), 'exp_year' => $date->format('Y'), 'active' => '1'] );
    }

    /**
     *
     * Get account preferred payment method details
     *
     */
    public function getRegbyAccountPreferred( $account_id )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Account ==========> ('.$account_id.')'.PHP_EOL; fwrite($this->myfile, $txt);
        if ( !$account_id ) return false;

        $now = new DateTime('now', new DateTimeZone( $this->session->config['time_zone'] ));

        $filter = array(
                        'account' => $account_id,
                        'preferred' => '1'
        );
        $account_payment_methods = $this->getAll( $filter );
//$txt = 'Preferred card with expired =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($account_payment_methods, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        // Expired
        foreach ( $account_payment_methods as $key => $account_payment_methods_temp )
        {
            $this->getRegbyId( $account_payment_methods_temp['id'] ) ;
//$txt = 'Payment method '.$account_payment_methods_temp->getId().PHP_EOL; fwrite($this->myfile, $txt);

            if ( $this->isExpired() ) unset( $account_payment_methods[$key]);
        }
//$txt = 'Preferred card without expired =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($account_payment_methods, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $account_payment_methods;
    }

    /**
     *
     * Get account payment methods active and not expired
     *
     */
    public function getAllActiveNotExpired( $account_id )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Account ==========> ('.$account_id.')'.PHP_EOL; fwrite($this->myfile, $txt);
//        if ( !$account_id ) return false;

        $filter = array(
                        'account' => $account_id,
                        'active' => '1',
        );
        $account_payment_methods = $this->getAll( $filter );
//$txt = 'All with expired =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($account_payment_methods, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        foreach ( $account_payment_methods as $key => $account_payment_methods_temp )
        {
            $this->getRegbyId( $account_payment_methods_temp['id'] );
//$txt = 'Payment method '.$account_payment_methods_temp->getId().PHP_EOL; fwrite($this->myfile, $txt);

            if ( $this->isExpired() ) unset( $account_payment_methods[$key]);
        }
//$txt = 'All without expired =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($account_payment_methods, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $account_payment_methods;
    }

    /**
     *
     * Check if card is expired
     *
     */
    public function isExpired()
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $now = new DateTime('now', new DateTimeZone( $this->session->config['time_zone'] ));

//$txt = 'Account payment method '.$this->getId().PHP_EOL; fwrite($this->myfile, $txt);
        $today = $now->format('Y-m-d');
//$txt = 'Today '.$today.PHP_EOL; fwrite($this->myfile, $txt);
        $card_expirity_date = $this->getExpYear().'-'.$this->getExpMonth().'-15';
//$txt = 'Card expirity date '.$card_expirity_date.PHP_EOL; fwrite($this->myfile, $txt);
        $date_end = DateTime::createFromFormat('Y-m-d', $card_expirity_date, new DateTimeZone($this->session->config['time_zone']));
        $date_end = $date_end->format('Y-m-d');
//$txt = 'date end '.$date_end.PHP_EOL; fwrite($this->myfile, $txt);
        $interval = $this->utils->get_interval_in_days( $today, $date_end );
//$txt = 'Interval '.$interval.PHP_EOL; fwrite($this->myfile, $txt);

//$txt = 'Expired '.(( $interval <= 0 )? 'Yes' : 'No').PHP_EOL; fwrite($this->myfile, $txt);
        return ( $interval <= 0 )? true : false;
    }
}
