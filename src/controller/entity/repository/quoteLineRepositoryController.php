<?php

namespace src\controller\entity\repository;

use \src\controller\entity\quoteController;

use \src\controller\entity\userController;
use \src\controller\entity\langTextController;

/**
 * Trait quoteLine
 * @package entity
 */
trait quoteLineRepositoryController
{
    /**
     *
     * Get all quote lines from quote
     *
     * @param $quote_id  int  Quote id
     *
     * @return $quote_lines  array All Quote lines filtered
     */
    public function getAllQuoteLinesFromQuote( $quote_id )
    {
        $quote_lines = $this->getAll( [ 'quote' => $quote_id ] );

        return $quote_lines;
    }

    /**
     *
     * Creates a quote line for a quote
     *
     * @param $quote object Quote
     * @param $units int Amount of products
     * @param $product_type string 'plan' or 'product'
     * @param $product object Product or Plan
     * @param $item object Object of the model entity (pe: widget, certification)
     * @param $discount string Amount (money) to discount of line total
     * @param $coupon object Coupon
     *
     * @return void
     */
    public function createQuoteLine( $quote, $units, $product_type, $plan_product, $item=NULL, $discount=0, $coupon=NULL )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Args'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Quote ('.$quote->getId().')'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Units ('.$units.')'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Product type ('.$product_type.')'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Product ('.$product->getId().')'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Discount ('.$discount.')'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Item Id ('.$item->getId().')'.PHP_EOL; fwrite($this->myfile, $txt);

        $this->setId('');
        $this->setQuote( $quote->getId() );
        $this->setUnits( $units );

        if ( $product_type == 'plan' )
        {

            $this->setProduct( $plan_product->getPlanKey() );
            $this->setPrice( '0' );
            $this->setAmount( '0' );
            $this->setTotal( '0' );
        }
        else
        {
            $this->setProduct( $plan_product->getId() );
            $this->setPrice( $plan_product->getPrice() );
            $amount = $units * $this->getPrice();
            $this->setAmount( $amount );
            $total = $amount - $discount;
            $this->setTotal( $total );
        }

        if ( $coupon ) $this->setCoupon( $coupon->getId() );
        if ( $item ) $this->setItem( $item->getId() );
        $this->setDiscount( $discount );
        $this->persist();

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     *
     * Get quote lines from his quote
     *
     */
    public function getLinesbyQuote( $quote_id )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Quote Id ==========> ('.$quote_id.')'.PHP_EOL; fwrite($this->myfile, $txt);
        if ( $quote_lines = $this->getAll( [ 'quote' => $quote_id ] ) )
        {
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
            return $quote_lines;
        }
        else
        {
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
            return false;
        }
    }
}
