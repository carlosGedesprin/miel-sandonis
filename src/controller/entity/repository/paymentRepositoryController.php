<?php

namespace src\controller\entity\repository;


/**
 * Trait payment
 * @package entity
 */
trait paymentRepositoryController
{
    /*
     *  Create a payment, called by paymentResultController
     *
     * @param $quote                object Quote
     * @param $payment_transaction  object Payment transaction
     *
     * @return void
     */
    public function createPayment( $quote, $payment_transaction )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $this->setId('');
        $this->setAccount( $quote->getAccount() );
        $this->setQuote( $quote->getId() );
        $this->setPaymentType( $quote->getPaymentType() ); //$quote['payment_type'] o $payment_transaction['payment_type']
        $this->setInstalment( '1' );
        $this->setDate( $payment_transaction->getDateReg() );
        $this->setAmount( $quote->getTotalToPay() );
        $this->setResult( '1' );
        $this->setTypeTrans( $payment_transaction->getPaymentType() );
        $this->setIdTrans( $payment_transaction->getId() );

        $this->persist();
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     *
     * Validations
     *
     * Return how many payments belongs to an account
     */
    public function howManyPaymentsOnAccount( $account )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $payments = $this->getAll( ['account' => $account ] );

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return sizeof( $payments );
    }
}
