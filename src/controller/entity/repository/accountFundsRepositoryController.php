<?php

namespace src\controller\entity\repository;

use \src\controller\entity\accountFundsSettingsController;
use \src\controller\entity\productController;

/**
 * Trait accountFunds
 * @package entity
 */
trait accountFundsRepositoryController
{
    /**
     *
     * Get account funds from his account
     *
     */
    public function getBalancebyAccount( $account )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Account ==========> ('.$account.')'.PHP_EOL; fwrite($this->myfile, $txt);
        if ( !$account ) return false;

        $filter = array( 'account' => $account );
        $operations = $this->getAll( $filter );

        $balance = 0;

        foreach ( $operations as $operation_temp )
        {
            $this->getRegbyId( $operation_temp['id'] );
            if ( !empty( $this->getCredit() ) ) $balance += intval( $this->getCredit() );
            if ( !empty( $this->getDebit() ) ) $balance -= intval( $this->getDebit() );
        }

//$txt = 'Balance '.$balance.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $balance;
    }
    /**
     *
     * Get all funds
     *
     */
    public function getGeneralBalance()
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $operations = $this->getAll();

        $balance = 0;

        foreach ( $operations as $operation_temp )
        {
            $this->getRegbyId( $operation_temp['id'] );
            if ( !empty( $this->getCredit() ) ) $balance += intval( $this->getCredit() );
            if ( !empty( $this->getDebit() ) ) $balance -= intval( $this->getDebit() );
        }

//$txt = 'Balance '.$balance.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $balance;
    }
    /**
     *
     * Check if there is a minimum balance and react as in fund settings
     *
     */
    public function enoughBalanceInAccount( $account )
    {
$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
$txt = 'Account ==========> ('.$account.')'.PHP_EOL; fwrite($this->myfile, $txt);
        if ( !$account ) return false;

        $account_fund_settings = new accountFundsSettingsController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $product = new productController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $balance = $this->getBalancebyAccount( $account );
$txt = 'Balance ======== '.$balance.PHP_EOL; fwrite($this->myfile, $txt);

        if ( $account_fund_settings->getRegbyAccount( $account ) )
        {
            if ( $account_fund_settings->getActive() && $account_fund_settings->getAutoFill() && $balance < $account_fund_settings->getMin() )
            {
                
            }
        }

        $minimum_can_be_invoiced = 0;
        $products = $product->getAll('', ' WHERE price > 0' );
$txt = 'Products ==========> '.PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r($products, TRUE));
        foreach( $products as $product_temp )
        {
            $product->getRegbyId( $product_temp['id'] );
$txt = 'Product '.$product->getName().' Price '.$product->getPrice().PHP_EOL; fwrite($this->myfile, $txt);

            if ( $minimum_can_be_invoiced == 0 ) $minimum_can_be_invoiced = $product->getPrice();

            if ( $minimum_can_be_invoiced > $product->getPrice() ) $minimum_can_be_invoiced = $product->getPrice();
$txt = 'Minimum  =============> '.$minimum_can_be_invoiced.PHP_EOL; fwrite($this->myfile, $txt);
        }
$txt = 'Minimum Final =================> '.$minimum_can_be_invoiced.PHP_EOL; fwrite($this->myfile, $txt);

        $minimum_can_be_invoiced = $minimum_can_be_invoiced * ( 1 + floatval($this->session->config['vat']) / 100);

        if ( $balance < $minimum_can_be_invoiced )
        {


        }


$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $balance;
    }
}
