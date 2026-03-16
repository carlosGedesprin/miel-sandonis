<?php
namespace src\util\utils;

/**
 * Trait quote
 * @package Utils
 */
trait quote
{
    /**
     * Get the quote key
     */
    public function getQuoteKey( $id )
    {
        return $this->db->fetchField('quote', 'quote_key', ['id' => $id]);
    }
}
