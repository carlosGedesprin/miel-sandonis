<?php

namespace src\controller\entity\repository;


/**
 * Trait accountPaymentDetails
 * @package entity
 */
trait accountPaymentDetailsRepositoryController
{
    /**
     *
     * Get all pay details from specific date
     */
    public function getAllAPDfromDate( $date )
    {
        return $this->db->querySQL( 'SELECT * FROM `account_pay_details` WHERE MONTH(`exp_date`) = '.$date->format('n').' AND YEAR(`exp_date`) = '.$date->format('Y') );
    }
}
