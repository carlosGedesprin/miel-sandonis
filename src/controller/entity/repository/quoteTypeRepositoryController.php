<?php

namespace src\controller\entity\repository;


/**
 * Trait quoteType
 * @package entity
 */
trait quoteTypeRepositoryController
{

    /**
     *
     * Get payment type from db
     */
    public static function getPaymentTypeMethod( $db, $quote_type )
    {
        return $db->fetchField('quote_type', 'payment_type', [ 'id' => $quote_type]);
    }
}
