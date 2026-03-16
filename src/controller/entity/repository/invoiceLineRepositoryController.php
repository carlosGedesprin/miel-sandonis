<?php

namespace src\controller\entity\repository;


/**
 * Trait invoiceLine
 * @package entity
 */
trait invoiceLineRepositoryController
{
    /**
     *
     * Get all invoice lines from invoice
     *
     * @param $invoice_id  int  Invoice id
     *
     * @return $invoice_lines  array All Invoice lines filtered
     */
    public function getAllInvoiceLinesFromInvoice( $invoice_id )
    {
        $invoice_lines = $this->getAll( [ 'invoice' => $invoice_id ] );

        return $invoice_lines;
    }

    /**
     *
     * Get all invoice lines from specific item and product
     *
     * @param $item_id     int  Service id, widget, certification...
     * @param $product_id  int  Product id
     *
     * @return $invoice_lines  array All Invoice lines filtered
     */
    public function getAllInvoiceLinesFromItemAndProduct( $item_id, $product_id )
    {
        $invoice_lines = $this->getAll( [ 'item' => $item_id, 'product' => $product_id ] );

        return $invoice_lines;
    }

    /**
     *
     * Create an Invoice line from a Quote line
     *
     * @param $quote_line   object  Quote line to be treated
     * @param $invoice_id   int     Invoice id
     *
     * @return void
     */
    public function createInvoiceLine( $quote_line, $invoice_id )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $this->setId( '' );
        $this->setInvoice( $invoice_id );
        $this->setItem( $quote_line->getItem() );
        $this->setUnits( $quote_line->getUnits() );
        $this->setProduct( $quote_line->getProduct() );
        $this->setDescription( $quote_line->getDescription() );
        $this->setPrice( $quote_line->getPrice() );
        $this->setAmount( $quote_line->getAmount() );
        $this->setDiscount( $quote_line->getDiscount() );
        $this->setTotal( $quote_line->getTotal() );
        $this->persist();

//fwrite($this->myfile, print_r($this->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }
}
