<?php
namespace src\util\utils;

use \src\controller\entity\paymentTypeController;

/**
 * Trait payment type
 * @package Utils
 */
trait payment_type
{
    /**
     * Get the payment type name
     */
    public function getPaymentTypeName( $id )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/util_'.__FUNCTION__.'.txt', 'a+') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Payment_type id ============= '.$id.PHP_EOL; fwrite($this->myfile, $txt);

        $payment_type = new paymentTypeController( array( 'env' => $_ENV, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => array(), 'db' => $this->db, 'utils' => array(), 'session' => array(), 'lang' => array() ) );
        $payment_type->getRegbyId( $id );
//$txt = 'Payment_type ============= '.$payment_type->getName().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($payment_type->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

//$txt = 'Payment_type Name ============= '.$payment_type->getName().PHP_EOL; fwrite($this->myfile, $txt);
//fclose($this->myfile);

        return $payment_type->getName();
    }
}