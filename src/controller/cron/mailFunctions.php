<?php

namespace src\controller\cron;

require_once APP_ROOT_PATH.'/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require_once APP_ROOT_PATH.'/vendor/phpmailer/phpmailer/src/SMTP.php';
require_once APP_ROOT_PATH.'/vendor/phpmailer/phpmailer/src/Exception.php';
use PHPMailer\PHPMailer\PHPMailer;
//use PHPMailer\PHPMailer\SMTP;
//use PHPMailer\PHPMailer\Exception;

use \src\util\lang;

use DateTime;
use DateTimeZone;

// Interesting article: https://kevinjmcmahon.net/articles/22/html-and-plain-text-multipart-email-/

class mailFunctions
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

//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/cron_mailFunctions_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = 'mailFunctions '.__METHOD__.' start ==============================================================='.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'POST =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $result = true;

        $this->processQueue();

//$txt = 'mailFunctions '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $result;
    }

    /**
     * Processes mails on db
     *
     */
    public function processQueue()
    {
//$txt = 'mailFunctions '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $amount = $this->db->fetchField( 'cron', 'size', ['process' => 'mail']);
        $now = (new DateTime("now", new DateTimeZone($this->session->config['time_zone'])))->format('Y-m-d H:i:s');

        $sql = 'SELECT * FROM `mail_queue` WHERE `sent` is NULL AND `send` < "' . $now . '" LIMIT ' . $amount;
//$txt = 'SQL (' . $sql . ')' . PHP_EOL; fwrite($this->myfile, $txt);
        $rows = $this->db->querySQL( $sql);
        foreach ( $rows as $mail_to_send)
        {
//fwrite($this->myfile, print_r($mail_to_send, TRUE));
//$txt = 'Method (' . $this->session->config['mail_method'] . ')' . PHP_EOL; fwrite($this->myfile, $txt);
            $mail_method = $this->session->config['mail_method'];
            if ( $this->$mail_method( $mail_to_send ) )
            {
//$txt = 'Message sent (' . $mail_to_send['id'] . ')' . PHP_EOL; fwrite($this->myfile, $txt);
                $this->logger->info('==============='.__METHOD__.' Mail sent ('.$mail_to_send['id'].') | To ('.$mail_to_send['to_name'].' <'.$mail_to_send['to_address'].') | Template ('.$mail_to_send['template'].') | Time ('.$now.') ===================================================');
                $this->db->updateArray('mail_queue', 'id', $mail_to_send['id'], ['sent' => $now]);
            }
            else
            {
//$txt = 'Message NOT sent (' . $mail_to_send['id'] . ')' . PHP_EOL; fwrite($this->myfile, $txt);
            }
        }
//$txt = 'mailFunctions '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     * parm mail_row    Mail object
     *
     */
    public function PHPMailer( $mail_row )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/mailFunctions_'.__FUNCTION__.'_'.$mail_row['id'].'.txt', 'a+') or die('unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
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
        //$mail->Debugoutput = 'error_log';
        /*
        $mail->Debugoutput = function($str, $level) {
$myfile = fopen(APP_ROOT_PATH.'/var/logs/mailFunctions_debug.txt', 'a+') or die('unable to open file!');
$txt = $level.': '.$str.PHP_EOL; fwrite($myfile, $txt);
fclose($myfile);
        };
        */
        /*
        $mail->Debugoutput = function($str, $level) {
            //if (strpos($str, 'CLIENT -> SERVER') === false) {
                //mysqli_query($db, "INSERT INTO maildebug SET level = '".mysqli_real_escape_string($db, $level)."', message = '".mysqli_real_escape_string($db, $str)."'");
//$txt = 'Debug -----'.PHP_EOL.PHP_EOL.$str.PHP_EOL; fwrite($this->myfile, $txt);
            echo $str;
            //}
        };
        */
        /*
        $debug = '';
        $mail->Debugoutput = function($str, $level) {
            //global $debug;
            $GLOBALS['debug'] .= "$level: $str\n";
        };
        */
        $mail->CharSet = 'utf-8';
        $mail->XMailer = $this->session->config['web_name'];
        $mail->Priority = ($mail_row['priority'] != '') ? $mail_row['priority'] : '3';  //Email priority (1 = High, 3 = Normal, 5 = low)

        $this->setHeaders($mail_row);
//$txt = 'This headers ---> '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->headers, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        foreach ($this->headers as $header)
        {
            $mail->addCustomHeader($header);
        }
        for ($i = 0; $i < count($this->headers); $i++) { unset($this->headers[$i]); }

        $mail->Host = $this->session->config['mail_host'];
        //$mail->Host = 'localhost';
        $mail->Port = $this->session->config['mail_port'];
        $mail->SMTPAuth = true;
        $mail->Username = $this->session->config['mail_username'];
        $mail->Password = $this->session->config['mail_password'];

        $mail->setFrom( $this->session->config['email_system_address'], $this->session->config['email_system_name'] );
        $mail->addReplyTo( $this->session->config['email_system_address'], $this->session->config['email_system_name'] );

        if ( $_ENV['env_env'] != 'prod' )
        {
            $mail->addAddress( $_ENV['developer'].'@'.$_ENV['domain'], $_ENV['developer'].' Testing Altira Automations');
            if ( $_ENV['developer'] == 'carlos' )
            {
                //$mail->addAddress('accedeme-software@gmail.com', 'Carlos Testing Gmail');
                //$mail->addAddress('carlos@openges.com', 'Carlos Testing Openges');
            }
            else if ( $_ENV['developer'] == 'silvia' )
            {
                //$mail->addAddress('silviaperez696@gmail.com', 'Silvia Testing Gmail');
                //$mail->addAddress('silvia@openges.com', 'Silvia Testing Openges');
            }
        }
        else
        {
            $mail->addAddress($mail_row['to_address'], $mail_row['to_name']);
        }

        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $mail_row['subject'];

        $mail_data = array();

        if ( $mail_row['assign_vars'] )
        {
//$txt = 'AssignVars ---> '.$mail_row['assign_vars'].PHP_EOL; fwrite($this->myfile, $txt);
            $assign_vars = unserialize( $mail_row['assign_vars'] );
//fwrite($this->myfile, print_r($assign_vars, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
            if ( is_array( $assign_vars ) && sizeof( $assign_vars ) )
            {
                foreach ( $assign_vars as $key => $value )
                {
                    $mail_data[$key] = $value;
                }
            }
        }

//        $mail_data['site_link'] = $this->startup->getUrlApp();

        if ( $mail_row['images'] ) {
            $images = unserialize($mail_row['images']);
            if (sizeof($images)) {
                foreach ($images as $key => $value) {
                    $mail_data[$key] = '/web/bundles/framework/images/mail/'.$this->session->config['app_skin'].'/'.$value;
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
        $mail_data['token'] = $random;
//$txt = '=== Mail data ===================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($mail_data, TRUE));
        $mail->addCustomHeader('List-Unsubscribe', '<mailto: '.$this->session->config['web_info_email'].'?subject=unsubscribe>, <'.$_ENV['protocol'].'://'.$_ENV['domain'].'/unsubscribe/'.$mail_row['to_address'].'/'.$random.'/>');

        if ( $mail_row['pre_header'] ) $mail_data['pre_header'] = $mail_row['pre_header'];

        // $env, $logger, $logger_err, $startup, $db, $utils, $session
        $lang_class = new lang( $this->env, $this->logger, $this->logger_err, $this->startup, $this->db, $this->utils, $this->session );
        $this->session->setLanguageCode2a( $mail_row['locale'] );
//$txt = '=== Mail locale ('.$mail_row['locale'].') Session lang code 2a ('.$this->session->getLanguageCode2a().')'.PHP_EOL; fwrite($this->myfile, $txt);
        $lang = $lang_class->getLangTexts();

//$txt = '=== Session ===================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->session, TRUE));
//$txt = '=== Session ===================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->session, TRUE));
//$txt = '=== Config ===================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->session->config, TRUE));
//$txt = '=== Env ======================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_ENV, TRUE));
//$txt = '=== Lang ======================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($lang, TRUE));
//$txt = '==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        if ( true )
        {
            $body = $this->twig->render('/app/'.$this->session->config['app_skin'].'/emails/'.$mail_row['template'].'.html.twig', array(
                'session' => $this->session,
                'app_config' => $this->session->config,
                'env' => $_ENV,
                'lang' => $lang,
                'data' => $mail_data,
                'row' => $mail_row,
            ));
            $mail->msgHTML($body, APP_ROOT_PATH);
//$txt = '==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = $body.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        }
        else
        {
            $mail->Body = $this->twig->render('app/'.$this->session->config['app_skin'].'/emails/'.$mail_row['template'].'.html.twig', array(
                'lang' => $lang,
                'data' => $mail_data,
                'row' => $mail_row,
            ));
        }
        //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        //send the message, check for errors
        if ( $mail->send() )
        {
//$txt = 'Message sent '.PHP_EOL; fwrite($this->myfile, $txt);
            //echo $mail->Body; //uncomment this to view the content
            $resultado = true;
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
            $resultado = false;

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
            $this->logger_err->error('Process (' . $mail_row['process'] . ')');
            $this->logger_err->error('Template (' . $mail_row['template'] . ')');
            $this->logger_err->error('Name from (' . $this->session->config['email_system_name'] . ')');
            $this->logger_err->error('Address from (' . $this->session->config['email_system_address'] . ')');
            $this->logger_err->error('Name to (' . $mail_row['to_name'] . ')');
            $this->logger_err->error('Address to (' . $mail_row['to_address'] . ')');
            $this->logger_err->error('==================================================');
            $this->logger_err->error('*************************************************************************');

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
//$txt = 'Debug Output '.PHP_EOL.PHP_EOL.$debug.PHP_EOL; fwrite($this->myfile, $txt);
//
//        if ( true )
//        {
//            $myfile_2 = fopen(APP_ROOT_PATH.'/var/logs/mailFunctions_'.__FUNCTION__.'.txt", "w") or die("Unable to open file!");
//            $txt = 'mailFunctions PHPMailer start ==============================================================='.PHP_EOL;
//            fwrite($myfile_2, $txt);
//            if ( $mail->SMTPDebug == 0 )
//            {
//                $txt = 'Message sent!'.PHP_EOL;
//                fwrite($myfile_2, $txt);
//            }
//            else
//            {
//                $txt = 'Message NOT sent!'.PHP_EOL;
//                $txt = '================================================================================='.PHP_EOL;
//                $txt = 'Host ('.$mail->host.')'.PHP_EOL;
//                $txt = 'Port ('.$mail->port.')'.PHP_EOL;
//                $txt = 'Username ('.$mail->username.')'.PHP_EOL;
//                $txt = 'Password ('.$mail->password.')'.PHP_EOL;
//                $txt = 'Mailer Error: ' . $mail->ErrorInfo.PHP_EOL;
//                $txt = '================================================================================='.PHP_EOL;
//                fwrite($myfile_2, $txt);
//            }
//            $txt = 'mailFunctions PHPMailer end ==============================================================='.PHP_EOL;
//            fwrite($myfile_2, $txt);
//            fclose($myfile_2);
//        }

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $resultado;
    }

    /**
     * Set the headers of an email
     *
     * parm mail_row    Mail object
     *
     */
    public function setHeaders( $mail_row )
    {
//$txt = '=== Headers ======================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        if ( !empty($mail_row['headers']) )
        {
//$txt = 'mail has headers on record'.PHP_EOL; fwrite($this->myfile, $txt);
            $r_headers = unserialize( $mail_row['headers'] );
//fwrite($this->myfile, print_r($r_headers, TRUE));
            $this->headers = array_merge( $this->headers, $r_headers );
        }
        $this->headers[] = 'X-AntiAbuse: Site servername - '.$this->session->config['mail_host'];    //mail_encode($config['server_name']);
        $this->headers[] = 'X-AntiAbuse: User_id - ' . md5( $mail_row['to_address'] );
        $this->headers[] = 'X-AntiAbuse: Username - ' . $mail_row['to_name'];    //mail_encode($user->data['username']);
        $this->headers[] = 'X-AntiAbuse: IP - ' . $_SERVER['SERVER_ADDR']; //$this->startup->getIP();
        $this->headers[] = 'X-MSMail-Priority: ' . (($mail_row['priority'] == '5') ? 'Low' : (($mail_row['priority'] == '3') ? 'Normal' : 'High'));
        $this->headers[] = 'X-MimeOLE: ' . $this->session->config['mail_host'];
        $this->headers[] = 'X-Origin: ' . $this->startup->getUrlApp();
//$txt = '=== Headers ======================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }

}