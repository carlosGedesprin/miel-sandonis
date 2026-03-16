<?php

namespace src\controller\api;

use \src\controller\baseController;

use \src\controller\entity\accountController;
use \src\controller\entity\userController;

use \src\controller\entity\productController;
use \src\controller\entity\langTextController;

use DateTime;
use DateTimeZone;

class automationController extends baseController
{
    /**
     * @Route('/api/get_product', name='/api_get_product')
     */
    public function getProductAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH . '/var/logs/api_automationController_' . __FUNCTION__ . '.txt', 'w') or die('Unable to open file!');
//$txt = '====================== ' . __METHOD__ . ' start ======================================' . PHP_EOL; fwrite($this->myfile, $txt);

        $api_request = $this->utils->checkAPIRequest();
//$txt = 'Api request =======>'.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($api_request, TRUE));

        if ( $api_request['status'] == 'KO' )
        {
//$txt = 'Error '.$api_request['data']['error_code'].' '.$api_request['data']['error_des'].' in Request ========>'.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($api_request, TRUE));
            $response = array(
                'status' => 'KO',
                'result' => 'API Request Error Code '.$api_request['data']['error_code'],
            );
        }
        else
        {
//$txt = 'No Errors'.PHP_EOL;fwrite($this->myfile, $txt);

            //$data_received = $api_request['data']['data']['data'];
            $data_received = $api_request['data']['data'];
//$txt = 'Data received =======>'.PHP_EOL;fwrite($this->myfile, $txt); fwrite($this->myfile, print_r($data_received, TRUE));

            $product = new productController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

            $product->getRegbyId( $data_received['product'] );

            $response = array(
                                'status' => 'OK',
                                'result' => $product->getReg()
            );
        }
//$txt = 'Response =======>'.PHP_EOL;fwrite($this->myfile, $txt); fwrite($this->myfile, print_r($response, TRUE));
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose($this->myfile);
        header('Content-type: application/json');
        echo json_encode( $response );
    }
}
