<?php

namespace src\controller\views\payments;

use \src\controller\baseViewController;

use \src\controller\entity\quoteController;
use \src\controller\entity\quoteLineController;

use src\controller\entity\productTypeController;
use \src\controller\entity\productController;

use \src\controller\entity\accountController;
use \src\controller\entity\userController;
use \src\controller\entity\mailQueueController;
use \src\controller\entity\langTextController;

use DateTime;
use DateTimeZone;
use Exception;

class paymentResultViewController extends baseViewController
{
    /*
     *  Processes a quote with total to pay 0
     *
     * @return object
     */
    public function paymentResultFree( $vars )
    {
        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']) );
//if ( $_ENV['env_env'] == 'dev') { $folder = '';} else { $folder = 'payments/';}
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/'.$folder.'/paymentResultViewController_'.__FUNCTION__.'_'.$now->format('Y_m_d').'.txt', 'a+') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==== '.$now->format('d-m-Y  H:i:s').' ============================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $quote = new quoteController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $quote_line = new quoteLineController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $product = new productController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $product_type = new productTypeController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $account = new accountController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $user = new userController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $data = array(
            'quote_key' => $vars['quote_key'],
            'product_name' => '',
            'next_action' => '',
        );

        $quote->getRegbyQuoteKey( $data['quote_key'] );

        $quote_lines = $quote_line->getAll( [ 'quote' => $quote->getId() ] );

        foreach ( $quote_lines as $quote_line_temp )
        {
            $quote_line->getRegbyId( $quote_line_temp['id'] );

            $product->getRegbyId( $quote_line->getProduct() );

            $product_type->getRegbyId( $product->getProductType() );

            $data['product_name'] = $product_type->getName();

        }


        $errors = array();
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//fclose( $this->myfile);

        return $this->twig->render('web/'.$this->session->config['website_skin'].'/payment/info_payment_free.html.twig', array(
            'data' => $data,
            'errors' => $errors
        ));
    }
}