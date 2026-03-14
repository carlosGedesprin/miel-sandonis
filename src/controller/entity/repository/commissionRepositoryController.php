<?php

namespace src\controller\entity\repository;

use src\controller\entity\productController;
use src\controller\entity\productTypeController;

use \src\controller\entity\accountController;
use \src\controller\entity\userController;
use \src\controller\entity\couponController;
use \src\controller\entity\langTextController;

use DateTime;
use DateTimeZone;

/**
 * Trait commission
 * @package entity
 */
trait commissionRepositoryController
{
    /**
     *
     * Get all commissions from one month
     */
    public function getAllCommissionsUnsettledOfMonth( $month, $year )
    {
        //return $this->db->fetchAll( $this->table, '*', NULL, ' WHERE `settlement` IS NULL AND MONTH(date) = `'.$month.'` AND YEAR(date) = `'.$year.'`');
        return $this->getAll( NULL, ' WHERE `settlement` IS NULL AND MONTH(date) = `'.$month.'` AND YEAR(date) = `'.$year.'`' );
    }
    /**
     *
     * Get all commissions from one settlement
     */
    public function getAllCommissionsOfSettlement( $settlement )
    {
        return $this->getAll( ['settlement' => $settlement] );
    }

    /**
     *  Creates a commission
     *
     * @param $quote_line   object Quote line to be treated
     * @param $item         object Service to be treated like widget, certification...
     * @param $account      object Account owner of the item, account to be invoiced
     * @param $invoice      object Invoice
     *
     * @throws
     * @return void
     */
    public function createCommission( $quote_line )
    {
        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']) );
$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $account = new accountController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $product = new productController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $product_type = new productTypeController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $commisionist_account = new accountController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $commisionist_user = new userController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $coupon = new couponController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

$txt = 'Quote ('.$quote_line->getQuote().') Quote line ('.$quote_line->getId().')'.PHP_EOL; fwrite($this->myfile, $txt);

        $product->getRegbyId( $quote_line->getProduct() );
$txt = 'Product ('.$product->getId().')'.PHP_EOL; fwrite($this->myfile, $txt);

        $product_type->getRegbyId( $product->getProductType() );
$txt = 'Product type ('.$product_type->getId().')'.PHP_EOL; fwrite($this->myfile, $txt);

$txt = 'Item is a '.$product_type->getController().PHP_EOL; fwrite($this->myfile, $txt);
        if ( $product_type->getController() )
        {
            require_once APP_ROOT_PATH . '/src/controller/entity/'.$product_type->getController().'Controller.php';
            $class_to_load = '\\src\\controller\\entity\\'.$product_type->getController().'Controller';
            $item = new $class_to_load( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        }
        else
        {
            $this->logger_err->error('*** Start *****************************************************************');
            $this->logger_err->error('** '.__METHOD__.' **');
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error( 'Product has no payedClass ');
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error( 'Quote: '.$quote_line->getQuote().' Line '.$quote_line->getId().' Product '.$product->getId().'' );
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error( 'Commission not created');
            $this->logger_err->error('*************************************************************************');
            exit();
        }
$txt = 'For domain '.$item->getDomainName().PHP_EOL; fwrite($this->myfile, $txt);

        $item->getRegbyId( $quote_line->getItem() );
$txt = 'Item found '.$item->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $item->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
/*
$txt = 'Coupon on item '.$item->getCoupon().PHP_EOL; fwrite($this->myfile, $txt);
        if ( empty( $item->getCoupon() ) )
        {
            if ( empty( $item->getAgent() ) )
            {
                $item->setAgent( $account->getAgent() );
                $item->persist();
$txt = 'Agent added to account from item ('.$account->getAgent().')'.PHP_EOL; fwrite($this->myfile, $txt);
            }
        }
        else
        {
            $coupon->getRegbyId( $item->getCoupon() );

            $item->setAgent( $coupon->getAgent() );
            $item->persist();
$txt = 'Agent added to item from coupon ('.$coupon->getAgent().')'.PHP_EOL; fwrite($this->myfile, $txt);
        }

        if ( empty( $item->getIntegrator() ) )
        {
            $item->setIntegrator( $account->getIntegrator() );
            $item->persist();
$txt = 'Integrator added to account from item ('.$account->getIntegrator().')'.PHP_EOL; fwrite($this->myfile, $txt);
        }
*/
        $commisionists = array();

        if ( !empty( $item->getAgent() ) ) $commisionists[] = $item->getAgent();
        if ( !empty( $item->getIntegrator() ) ) $commisionists[] = $item->getIntegrator();

$txt = 'accounts to commission'.PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r( $commisionists, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        if ( !empty( $commisionists ) )
        {
            foreach ( $commisionists as $commisionist )
            {
                $this->setId( '' );

                $commisionist_account->getRegbyId( $commisionist );
$txt = 'account to commission ('.$commisionist_account->getId().')'.PHP_EOL; fwrite($this->myfile, $txt);

                $commisionist_user->getRegbyId( $commisionist_account->getMainUser() );
//$txt = 'main user from commisionist ('.$commisionist_user->getId().')'.PHP_EOL; fwrite($this->myfile, $txt);

                if ( empty( $item->getCoupon() ) || $coupon->getAgent() != $commisionist_account->getId() )
                {
                    $percent_commission = $commisionist_account->getCommissionPercent();

                    // Comisión de %1s - %2s para el dominio %3s
                    $commission_no_coupon_description_text = langTextController::getLangText( $this->utils, $commisionist_user->getLocale(), 'COMMISSION_DESCRIPTION_TEXT_NO_COUPON' );
//$txt = 'Commision description text no coupon raw for COMMISSION_NO_COUPON_DESCRIPTION_TEXT ('.$commission_no_coupon_description_text.') in '.$commisionist_user->getLocale().PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Product type name ('.$product_type_name.') Product name ('.$product_name.' Doamin name ('.$item->getDomainName().')'.PHP_EOL; fwrite($this->myfile, $txt);
                    $this->setDescription( sprintf( $commission_no_coupon_description_text, $product_type->getName(), $product->getName(), $item->getDomainName() ) );
                }
                else
                {
                    $coupon->getRegbyAgent( $commisionist_account->getId() );
                    $percent_commission = $coupon->getCommissionPercent();

                    $commission_coupon_description_text = langTextController::getLangText( $this->utils, $commisionist_user->getLocale(), 'COMMISSION_DESCRIPTION_TEXT_COUPON' );
//$txt = 'Commision description text coupon raw for COMMISSION_COUPON_DESCRIPTION_TEXT ('.$commission_coupon_description_text.')'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Product type name ('.$product_type_name.') Product name ('.$product_name.' Doamin name ('.$item->getDomainName().')'.PHP_EOL; fwrite($this->myfile, $txt);
                    $this->setDescription( sprintf( $commission_coupon_description_text, $product_type->getName(), $product->getName(), $item->getDomainName()) );
                }
$txt = 'Commision description text ('.$this->getDescription().') in '.$commisionist_user->getLocale().PHP_EOL; fwrite($this->myfile, $txt);

                $total_commission = round( intval( $quote_line->getTotal() ) * ( intval( $percent_commission ) / 10000) );
$txt = 'Total commission ('.$total_commission.')'.PHP_EOL; fwrite($this->myfile, $txt);

                $this->setAccount( $commisionist_account->getId() );
                //$this->setInvoice( $invoice->getId() );
                $this->setDate( $now );
                $this->setInvoiceNet( $quote_line->getTotal() );
                $this->setCommissionPercent( $percent_commission );
                $this->setTotal( $total_commission );
                $this->setPayed( '0' );

                $this->persist();
$txt = 'Commission created'.PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r( $this->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
            }
        }
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }
}
