<?php

namespace src\util;

require_once APP_ROOT_PATH.'/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require_once APP_ROOT_PATH.'/vendor/phpmailer/phpmailer/src/SMTP.php';
require_once APP_ROOT_PATH.'/vendor/phpmailer/phpmailer/src/Exception.php';
use PHPMailer\PHPMailer\PHPMailer;
//use PHPMailer\PHPMailer\SMTP;
//use PHPMailer\PHPMailer\Exception;
use src\util\lang;

use DateTime;
use DateTimeZone;

// Interesting article: https://kevinjmcmahon.net/articles/22/html-and-plain-text-multipart-email-/

class mailFunctionsOld
{
    private $env;
    private $logger;
    private $logger_err;
    private $startup;
    private $db;
    private $utils;
    private $session;
    private $lang;
    private $twig;

    private $myfile;

    private $headers;

    public function __construct( $env, $logger, $logger_err, $startup, $db, $utils, $session, $lang, $twig )
    {
        $this->env = $env;
        $this->logger = $logger;
        $this->logger_err = $logger_err;
        $this->startup = $startup;
        $this->db = $db;
        $this->utils = $utils;
        $this->session = $session;
        $this->lang = $lang;
        $this->twig = $twig;

        $now = (new DateTime("now", new DateTimeZone($_ENV['time_zone'])))->format('Y-m-d H:i:s');
$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/util_mailFunctions_'.__FUNCTION__.'.txt', 'a+') or die('Unable to open file!');
$txt = '====================== '.__METHOD__.' start '.$now.'==============================================================='.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'POST =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $this->processQueue();

$txt = '====================== '.__METHOD__.' end '.$now.' ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
fclose($this->myfile);
    }

    /**
     * Processes mails on db
     *
     */
    public function processQueue()
    {
//$txt = '====================== '.__METHOD__.' start == '.$now.' ============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $now = (new DateTime("now", new DateTimeZone($_ENV['time_zone'])))->format('Y-m-d H:i:s');

        $amount = $this->db->fetchField( 'cron', 'size', ['process' => 'mail']);

        $now = (new DateTime("now", new DateTimeZone($_ENV['time_zone'])))->format('Y-m-d H:i:s');

        $sql = 'SELECT * FROM `mail_queue` WHERE `sent` is NULL AND `send` < "' . $now . '" LIMIT ' . $amount;
//$txt = 'SQL (' . $sql . ')' . PHP_EOL; fwrite($this->myfile, $txt);
        $rows = $this->db->querySQL( $sql);
//$txt = 'Rows (' . sizeof( $rows ) . ')'.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($rows, TRUE));
        foreach ( $rows as $mail_to_send )
        {
//$txt = 'Mail to be sent -----------------------------------'.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($mail_to_send, TRUE));

//$txt = 'Method (' . $this->session->config['mail_method'] . ')' . PHP_EOL; fwrite($this->myfile, $txt);
            $mail_method = 'PHPMailer';

            if ( $this->$mail_method( $mail_to_send ) )
            {
//$txt = 'Message sent (' . $mail_to_send['id'] . ')' . PHP_EOL; fwrite($this->myfile, $txt);
                $this->logger->info('==============='.__METHOD__.' Mail sent ('.$mail_to_send['id'].') | To ('.$mail_to_send['to_name'].' <'.$mail_to_send['to_address'].') | Template ('.$mail_to_send['template'].') | Time ('.$now.') ===================================================');
                $this->db->updateArray('mail_queue', 'id', $mail_to_send['id'], ['sent' => $now]);
            }
            else
            {
//$txt = 'Message NOT sent (' . $mail_to_send['id'] . ')' . PHP_EOL; fwrite($this->myfile, $txt);
                $this->db->updateArray('mail_queue', 'id', $mail_to_send['id'], ['sent' => '2000-01-01 01:01:01']);
            }
        }
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     * parm mail_row    Mail object
     *
     */
    public function PHPMailer( $mail_row )
    {
$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'sending email ------------------'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($mail_row, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $mail = new PHPMailer;

        $mail->isSMTP();                        //Tell PHPMailer to use SMTP
        $mail->SMTPOptions = array(
                                    'ssl' => array(
                                                    'verify_peer' => false,
                                                    'verify_peer_name' => false,
                                                    'allow_self_signed' => true
                                                )
                            );
        $mail->SMTPDebug = 0;                   //Enable SMTP debugging 0 = off (for production use) 1 = client messages 2 = client and server messages
        $mail->Debugoutput = 'html';            //Ask for HTML-friendly debug output
        $mail->CharSet = 'utf-8';
        $mail->XMailer = $this->session->config['web_name'];
        $mail->Priority = ($mail_row['priority'] != '') ? $mail_row['priority'] : '3';  //Email priority (1 = High, 3 = Normal, 5 = low)

        $this->setHeaders($mail_row);
        foreach ($this->headers as $header)
        {
            $mail->addCustomHeader($header);
        }
        $mail->Host = $_ENV['mail_host']; //Set the hostname of the mail server
        //$mail->Host = 'localhost';	//Set the hostname of the mail server
        $mail->Port = $_ENV['mail_port'];    //Set the SMTP port number - likely to be 25, 465 or 587
        $mail->SMTPAuth = true;    //$config['smtp_auth']Whether to use SMTP authentication
        $mail->Username = $_ENV['mail_username'];    //Username to use for SMTP authentication
        $mail->Password = $_ENV['mail_password'];    //Password to use for SMTP authentication

        $mail->setFrom($_ENV['mail_sender_address'], $_ENV['mail_sender_name']);    //Set who the message is to be sent from
        $mail->addReplyTo($_ENV['mail_sender_address'], $_ENV['mail_sender_name']);    //Set an alternative reply-to address

        if ( $_ENV['env_env'] != 'prod' )
        {
            $mail->addAddress('carlos@accedeme.com', 'Carlos Testing');
        }
        else
        {
            $mail->addAddress($mail_row['to_address'], $mail_row['to_name']);
        }

        $mail->isHTML(true);                                  // Set email format to HTML

        //$now = (new DateTime("now", new DateTimeZone($_ENV['time_zone'])))->format('Y-m-d H:i:s');
        $mail->Subject = $mail_row['subject']; //.' '.$now;

        $mail_data = array();

        if ( $mail_row['assign_vars'] )
        {
//$txt = 'AssignVars ---> '.$mail_row['assign_vars'].PHP_EOL; fwrite($this->myfile, $txt);
            $assign_vars = unserialize($mail_row['assign_vars']);
//fwrite($this->myfile, print_r($assign_vars, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
            if ( is_array( $assign_vars ) && sizeof( $assign_vars ) )
            {
                foreach ( $assign_vars as $key => $value )
                {
                    $mail_data[$key] = $value;
                }
            }
        }

        $mail_data['site_link'] = $this->startup->getUrlApp();

        if ( $mail_row['images'] ) {
            $images = unserialize($mail_row['images']);
            if (sizeof($images)) {
                foreach ($images as $key => $value) {
                    $mail_data[$key] = '/web/bundles/framework/images/mail/default/'.$value;
                }
            }
        }
        
        if ( $mail_row['attached'] ) {
            $attached = unserialize($mail_row['attached']);
            if (sizeof($attached)) {
                foreach ($attached as $key => $value) {
                    $mail->AddAttachment( $value );
                }
            }
        }

        // Unsuscribe token
        $random = base64_encode( random_bytes(5) );
        $random = str_replace( '/' , '$' , $random);
        $mail_data['token'] = substr($random, 1, 23);
//$txt = '=== Mail data ===================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($mail_data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        $mail->addCustomHeader('List-Unsubscribe', '<mailto: '.$_ENV['mail_sender_address'].'?subject=unsubscribe>, <'.$_ENV['protocol'].'://'.$_ENV['domain'].'/unsubscribe/'.$mail_row['to_address'].'/'.$random.'/>');

        if ( $mail_row['pre_header'] ) $mail_data['pre_header'] = $mail_row['pre_header'];

        $lang_class = new lang( $this->env, $this->logger, $this->logger_err, $this->startup, $this->db, $this->utils, $this->session );
        $lang = $lang_class->getLangTexts();
//$txt = '=== Lang ===================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($lang, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        /*
        require_once APP_ROOT_PATH.'/src/util/lang.php';
        $lang_class = new \src\util\lang( $this->db, $mail_row['locale'] );
        $lang = $lang_class->getLangTexts();
        */

//$txt = '=== Session ===================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->session, TRUE));
//$txt = '=== Env ======================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_ENV, TRUE));
//$txt = '==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        if ( true )
        {
            $body = $this->twig->render('/app/default/emails/'.$mail_row['template'].'.html.twig', array(
                'session' => $this->session,
                'config' => $this->session->config,
                'env' => $_ENV,
                'lang' => $lang,
                'data' => $mail_data,
                'row' => $mail_row,
            ));
            $mail->msgHTML($body, APP_ROOT_PATH);
$txt = '==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
$txt = $body.PHP_EOL; fwrite($this->myfile, $txt);
$txt = '==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        }
        else
        {
            $mail->Body = $this->twig->render('app/default/emails/'.$mail_row['template'].'.html.twig', array(
                //'lang' => $lang,
                'data' => $mail_data,
                'row' => $mail_row,
            ));
        }
        //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

//$txt = 'Message to send '.$mail_row['id'] .PHP_EOL; fwrite($this->myfile, $txt);
        //send the message, check for errors
        if ( $mail->send() )
        {
//echo ' --> Message sent '.$mail_row['to_address'].PHP_EOL;
$txt = 'Message sent '.PHP_EOL; fwrite($this->myfile, $txt);
            //echo $mail->Body; //uncomment this to view the content
            $result = true;
            if ( !empty( $mail_row['attached'] ) )
            {
                $attached = unserialize( $mail_row['attached'] );
                if ( sizeof( $attached ) ){
                    foreach ($attached as $key => $value){
                        unlink( $value );
                    }
                }
            }
        }
        else
        {
            $result = false;

            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error('PHPMailer Error -- Message not sent.');
            $this->logger_err->error('==================================================');
            $this->logger_err->error('Mail id (' . $mail_row['id'] . ')');
            $this->logger_err->error('==================================================');
            $this->logger_err->error('Host (' . $mail->Host . ')');
            $this->logger_err->error('Port (' . $mail->Port . ')');
            $this->logger_err->error('Username (' . $mail->Username . ')');
            $this->logger_err->error('Password (' . $mail->Password . ')');
            $this->logger_err->error('Mailer Error: ' . $mail->ErrorInfo);
            $this->logger_err->error('==================================================');
            $this->logger_err->error('*************************************************************************');

//echo '---> Message NOT sent!' . PHP_EOL;
//$txt = 'Message NOT sent!' . PHP_EOL;
//$txt .= '=================================================================================' . PHP_EOL;
//$txt .= 'Mail id (' . $mail_row['id'] . ')' . PHP_EOL;
//$txt .= 'Host (' . $mail->Host . ')' . PHP_EOL;
//$txt .= 'Port (' . $mail->Port . ')' . PHP_EOL;
//$txt .= 'Username (' . $mail->Username . ')' . PHP_EOL;
//$txt .= 'Password (' . $mail->Password . ')' . PHP_EOL;
//$txt .= 'Mailer Error: ' . $mail->ErrorInfo . PHP_EOL;
//$txt .= '=================================================================================' . PHP_EOL;
//fwrite($this->myfile, $txt);
        }

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
        return $result;
    }
    /**
     * Set the headers of an email
     *
     * parm mail_row    Mail object
     *
     */
    public function setHeaders( $mail_row )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
        if ( $mail_row['headers'] != '' )
        {
            $r_headers = unserialize( $mail_row['headers'] );
            $this->headers = array_merge( $this->headers, $r_headers );
        }
        $this->headers[] = 'X-AntiAbuse: Site servername - ';    //mail_encode($config['server_name']);
        $this->headers[] = 'X-AntiAbuse: User_id - ' . md5($mail_row['to_address']);
        $this->headers[] = 'X-AntiAbuse: Username - ' . $mail_row['to_name'];    //mail_encode($user->data['username']);
        $this->headers[] = 'X-AntiAbuse: IP - ' . $_SERVER['SERVER_ADDR']; //$this->startup->getIP();
        $this->headers[] = 'X-MSMail-Priority: ' . (($mail_row['priority'] == '5') ? 'Low' : (($mail_row['priority'] == '3') ? 'Normal' : 'High'));
        $this->headers[] = 'X-MimeOLE: Mi Web Accesible.com';
        $this->headers[] = 'X-Origin: https://miwebaccesible.com';
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
    }

}