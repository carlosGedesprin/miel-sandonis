<?php
namespace src\util\utils;

/**
 * Trait coupon
 * @package Utils
 */
trait coupon
{
    /**
     * Get the plan monthly product by plan key
     */
    public function getCoupon( $coupon_id )
    {
        return $this->db->fetchOne('coupon', '*', ['id' => $coupon_id]);
    }
}