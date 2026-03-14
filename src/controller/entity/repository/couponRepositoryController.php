<?php

namespace src\controller\entity\repository;

use \src\controller\entity\productController;
use \src\controller\entity\planController;
use \src\controller\entity\quoteLineController;

/**
 * Trait coupon
 * @package entity
 */
trait couponRepositoryController
{
    /**
     *
     * Calculate discount when applying this coupon
     *
     * @param $amount string Figure to calculate the amount of the discount, warning it comes with no decimal point or comma and 2 decimal positions
     *
     * @return $ammount_off string Discount amount with no decimal point or comma and 2 decimal positions
     */
    public function getDiscountAmount( $amount )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Price ==========> ('.$price.')'.PHP_EOL; fwrite($this->myfile, $txt);
        if ( $this->getDiscountType() == 'amount' )
        {
            $amount_off = $this->getDiscount();
//$txt = 'Amount off ==========> ('.$amount_off.')'.PHP_EOL; fwrite($this->myfile, $txt);
        }
        elseif ( $this->getDiscountType() == '%' )
        {
            $percent = $this->getDiscount() / 100;
//$txt = 'Percentage  ==========> ('.$percent.')'.PHP_EOL; fwrite($this->myfile, $txt);

            $amount_off = $amount * $percent / 100;
//$txt = 'Percentage Amount off ==========> ('.$amount_off.')'.PHP_EOL; fwrite($this->myfile, $txt);
        }
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $amount_off;
    }
    /**
     *
     * Calculate amount when applying this coupon
     *
     * @param $amount string Amount to discount this coupon, warning it comes with no decimal point or comma and 2 decimal positions
     *
     * @return $discounted_amount string New amount with discount applied
     *
     */
    public function getDiscountedAmount( $amount )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $discounted_amount = $amount - $this->getDiscountAmount( $amount );
//$txt = 'Discounted amount ==========> ('.$discounted_amount.')'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $discounted_amount;
    }

    /**
     *
     * Check if coupon can be used, otherwise take it off the item
     *
     * @param $item object Object (widget, certification...)
     *
     * @return boolean Result of checks
     */
    public function checkItemCoupon( $item )
    {
$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $this->getRegbyId( $item->getCoupon() );
$txt = 'Coupon ============> '.$this->getId().PHP_EOL; fwrite($this->myfile, $txt);
$txt = print_r($this->getReg(), true).PHP_EOL; fwrite($this->myfile, $txt);

        $product = new productController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $product->getRegbyId( $item->getProduct() );
$txt = 'Product ============> '.$product->getId().PHP_EOL; fwrite($this->myfile, $txt);
$txt = print_r($product->getReg(), true).PHP_EOL; fwrite($this->myfile, $txt);

        $plan = new planController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $plan->getRegbyId( $product->getPlan() );
$txt = 'Plan ============> '.$plan->getId().PHP_EOL; fwrite($this->myfile, $txt);
$txt = print_r($plan->getReg(), true).PHP_EOL; fwrite($this->myfile, $txt);

        $quote_line = new quoteLineController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $times_used = $quote_line->getAll( [ 'item' => $item->getId(), 'product' => $product->getId(), 'coupon' => $this->getId() ] );
$txt = 'Times coupon used ============> '.sizeof( $times_used ).PHP_EOL; fwrite($this->myfile, $txt);
$txt = print_r($plan->getReg(), true).PHP_EOL; fwrite($this->myfile, $txt);

        $max_times_coupon_discount = intval( $this->getNumPeriod() );
$txt = 'Max times coupon can be used ============> '.$max_times_coupon_discount.PHP_EOL; fwrite($this->myfile, $txt);

        if ( count( $times_used ) >= $max_times_coupon_discount )
        {
$txt = '---------- Coupon finished ---------- '.PHP_EOL; fwrite($this->myfile, $txt);
$txt = '---------- Delete coupon from item ---------- '.PHP_EOL; fwrite($this->myfile, $txt);
            $item->setCoupon( NULL );
            $item->persist();
            return false;
        }
$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return true;
    }
}
