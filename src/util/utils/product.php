<?php
namespace src\util\utils;

/**
 * Trait product
 * @package Utils
 */
trait product
{
    /**
     * Get the product
     */
    public function getProduct( $id )
    {
        return $this->db->fetchOne('product', '*', ['id' => $id]);
    }

    /**
     * Get the product name
     */
    public function getProductName( $id )
    {
        return $this->db->fetchField('product', 'name', ['id' => $id]);
    }

    /**
     * Get the product demo period
     */
    public function getProductDemoPeriod( $id )
    {
        $product = $this->db->fetchOne('product', 'period_demo, num_period_demo', ['id' => $id]);
        return array( $product['period_demo'], $product['num_period_demo'] );
    }

    /**
     * Get the product demo period
     */
    public function getProductPrice( $id )
    {
        return $this->db->fetchOne('product', 'price', ['id' => $id]);
    }
}
