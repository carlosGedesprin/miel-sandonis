<?php

namespace src\controller\entity\repository;


/**
 * Trait Payment Transaction
 * @package entity
 */
trait paymentTransactionRepositoryController
{
    /**
     *
     * Get all payment transaction with a account payment method
     *
     * @param $account_payment_method_id string Account payment method id
     *
     * @return array List of payment transaction with an account payment method
     */
    public function getPaymentTransactionWithAccountPaymentMethod( $account_payment_method )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Account payment method =====> ('.$account_payment_method.')'.PHP_EOL; fwrite($this->myfile, $txt);

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $this->getAll( ['account_payment_method' => $account_payment_method] );
    }
}
