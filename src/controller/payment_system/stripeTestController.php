<?php

namespace src\controller\payment_system;

use \src\controller\baseController;

use \src\controller\entity\paymentTransactionController;
use \src\controller\entity\quoteController;
use \src\controller\entity\leadFundingController;
use \src\controller\payment_system\paymentResultController;
use \src\controller\entity\mailQueueController;

use DateTime;
use DateTimeZone;

class stripeTestController extends baseController
{
    private $payment_system = 'Stripe';

    /**
     * 
     * Webhook Stripe, replying to test route
     * 
     * @Route("/payments/stripe_things_test", name="payments_stripe_things_test")
     */
    public function WebhookStripe()
    {
        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']) );

        $payload = @file_get_contents("php://input");
        $event = null;

$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/aa_payment_stripe_webHook_TEST.txt', 'a+') or die('Unable to open file!');
$txt = '====================== '.__METHOD__.' start ==== '.$now->format('d-m-Y  H:i:s').' ==========================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];

        // Invalid payload
        $this->logger_err->error('*************************************************************************');
        $this->logger_err->error('Stripe payment Error -> TEST route.');
        $this->logger_err->error('*************************************************************************');
        $this->logger_err->error('Payload ('.$payload.')');
        $this->logger_err->error('==================================================');
        $this->logger_err->error('Signature header ('.$sig_header.')');
        $this->logger_err->error('==================================================');
        $this->logger_err->error('Endpoint secret on config ('.$_ENV['stripe_w'].')');
        $this->logger_err->error('*************************************************************************');
$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
fclose($this->myfile);
        http_response_code(417); // 407 in production
        exit();

    }
}