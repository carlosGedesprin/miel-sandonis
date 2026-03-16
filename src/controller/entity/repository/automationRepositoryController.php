<?php

namespace src\controller\entity\repository;

use \src\controller\entity\accountController;
use \src\controller\entity\productTypeController;
use \src\controller\entity\productController;
use \src\controller\entity\langTextController;

use DateTime;
use DateTimeZone;
use DateInterval;
use Exception;

/**
 * Trait automation
 * @package entity
 */
trait automationRepositoryController
{
    /**
     *
     * Create an automation
     *
     * @param $account  object  Account object
     * @param $billing_account  object  Account object
     * @param $product  object  Product object
     *
     * @throws
     * @return void
     */
    public function createAutomation( $account, $billing_account, $product )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Params account ('.$account->getId().') billing account ('.$billing_account->getId().') product ('.$product->getId().')'.PHP_EOL; fwrite($this->myfile, $txt);
        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']) );

        $this->setAccount( $account->getId() );
        $this->setBillingAccount( $billing_account->getId() );
        $this->setProduct( $product->getId() );
        $this->setDateReg( $now );
        $this->setActive( '0' );

//$txt = 'New automation ==========> ('.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        $this->persistORL();
        $this->setAutomationKey( md5( $this->getId() ) );
        $this->persistORL();
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     *
     * Procesing when a automation is paid.
     *
     * @param $quote_line   object  Quote line to be treated
     *
     * @throws
     * @return $quote_line  object Quote line treated
     */
    public function paidAction( $quote_line )
    {
        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']) );
//$txt = '================================== '.__METHOD__.' start ============= '.$now->format('d-m-Y  H:i:s').' ================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Quote line received '.$quote_line->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $quote_line->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $product = new productController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $this->getRegbyId( $quote_line->getItem() );
//$txt = 'Automation '.$this->getId().' - '.$this->getDomainName().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $this->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $account = new accountController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $account->getRegbyId( $this->getAccount() );
//$txt = 'Account '.$account->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $account->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $product->getRegbyId( $quote_line->getProduct() );
//$txt = 'Product '.$product->getId().' - '.$product->getName().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $product->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $this->setCoupon( $quote_line->getCoupon() );

//$txt = 'Date end '.$this->getDateEnd()->format('Y-m-d').PHP_EOL; fwrite($this->myfile, $txt);
        list($date_start, $date_end) = $product->calc_renew_dates( $this->getDateEnd()->format('Y-m-d') );
        $this->setDateStart( $date_start );
        $this->setDateEnd( $date_end );
//$txt = 'New dates '.$this->getDateStart()->format('d-m-Y').' - '.$this->getDateEnd()->format('d-m-Y').PHP_EOL; fwrite($this->myfile, $txt);
        $this->setProduct( $quote_line->getProduct() );
/*
        $this->setAgent( $account->getAgent() );
        $this->setIntegrator( $account->getIntegrator() );
*/
        $this->setActive( '1' );
//$txt = 'Automation paid : '.$this->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        $this->persistORL();

        $quote_line->setDescription( $this->createQLDescription( $account->getLocale(), $date_start, $date_end ) );
//$txt = 'New quote line description ===> '.$quote_line->getDescription().PHP_EOL; fwrite($this->myfile, $txt);

//$txt = 'Quote line: '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $quote_line->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        $quote_line->persistORL();

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $quote_line;
    }

    /**
     *
     * Create Quote Line description
     *
     * @param $locale      string Locale for texts
     * @param $date_start  object Date start
     * @param $date_end    object Date end
     *
     * @throws
     * @return $description string Quote line description
     */
    public function createQLDescription( $locale, $date_start=NULL, $date_end=NULL )
    {
        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']) );
//$txt = '====================== '.__METHOD__.' start ============= '.$now->format('d-m-Y  H:i:s').' ======================================================'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Automation ('.$this->getId().') Start ('.(($date_start)? $date_start->format('d-m-Y') : '').') End ('.(($date_end)? $date_end->format('d-m-Y') : '').')'.PHP_EOL; fwrite($this->myfile, $txt);

        $product_type = new productTypeController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $product = new productController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $product->getRegbyId( $this->getProduct() );
        $product_type->getRegbyId( $product->getProductType() );
        $text_product = langTextController::getLangText( $this->utils, $locale, $product_type->getNameKey() );
//$txt = 'QL Description text '.$text_product.PHP_EOL; fwrite($this->myfile, $txt);

        //$text_automation = langTextController::getLangText( $this->utils, $locale,'AUTOMATION' );
        $text_automation = $text_product;
        $text_from = langTextController::getLangText( $this->utils, $locale,'INVOICE_LINE_FROM' );
        $text_to = langTextController::getLangText( $this->utils, $locale,'INVOICE_LINE_TO' );

        $description = $text_automation.' '.$this->session->config['web_name'].': ';
//        $description .= ' '.$this->getDomainName();

        $description .= ( $date_start )? ' '.$text_from.': '.$date_start->format('d-m-Y') : '';
        $description .= ( $date_end )? ' '.$text_to.': '.$date_end->format('d-m-Y') : '';

//$txt = 'Description ====>'.$description.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $description;
    }

    /**
     *
     * Create Invoice Line description
     *
     * @param $locale      string Locale for texts
     * @param $date_start  object Date start
     * @param $date_end    object Date end
     *
     * @throws
     * @return $description string Invoice line description
     */
    public function createILDescription( $locale, $date_start=NULL, $date_end=NULL )
    {
        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']) );
//$txt = '====================== '.__METHOD__.' start ============= '.$now->format('d-m-Y  H:i:s').' ======================================================'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Automation ('.$this->getId().') Start ('.(($date_start)? $date_start->format('d-m-Y') : '').') End ('.(($date_end)? $date_end->format('d-m-Y') : '').')'.PHP_EOL; fwrite($this->myfile, $txt);
/*
        $product = new productController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $product_type = new productTypeController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $product->getRegbyId( $this->getProduct() );
//$txt = 'Product '.$product->getId().PHP_EOL; fwrite($this->myfile, $txt);
        $product_type->getRegbyId( $product->getProductType() );
//$txt = 'Product type '.$product_type->getId().PHP_EOL; fwrite($this->myfile, $txt);
        $text_product = langTextController::getLangText( $this->utils, $locale, $product_type->getNameKey() );
//$txt = 'IL Description text '.$text_product.PHP_EOL; fwrite($this->myfile, $txt);

        //$text_automation = langTextController::getLangText( $this->utils, $locale,'AUTOMATION' );
        $text_automation = $text_product;
        $text_from = langTextController::getLangText( $this->utils, $locale,'QUOTE_LINE_FROM' );
        $text_to = langTextController::getLangText( $this->utils, $locale,'QUOTE_LINE_TO' );

        $description = $text_automation.' '.$this->session->config['web_name'].': ';
        $description .= ' '.$this->getDomainName();

////////////////////////////////// Special case: Kit Digital start ///////////////////////////////
        $website_extra = new websiteExtraController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        if ( $website_extra->getRegbyWebsite( $this->getId() ) && !empty( $website_extra->getProceedings() ) )
        {
            $description .= ' ref. '.$website_extra->getProceedings();
        }
////////////////////////////////// Special case: Kit Digital end /////////////////////////////////

        $description .= ( $date_start )? ' '.$text_from.': '.$date_start->format('d-m-Y') : '';
        $description .= ( $date_end )? ' '.$text_to.': '.$date_end->format('d-m-Y') : '';

//$txt = 'Description ====>'.$description.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $description;
*/
    }
}
