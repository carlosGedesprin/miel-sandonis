<?php

namespace src\controller;

use \src\controller\baseViewController;
use \src\controller\entity\cronController as cron_entity;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class cronController extends baseViewController
{
    private $run_cron = false;
    
    protected $myfile;

    /**
     * @Route("/cron/", name="webcron")
     */
    public function webcronAction()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/cronController_'.__FUNCTION__.'.txt', 'a+') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        // Output transparent gif
        header('Cache-Control: no-cache');
        header('Content-type: image/gif');
        header('Content-length: 43');
        header('Connection: Close');
        echo base64_decode('R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==');
        // Flush here to prevent browser from showing the page as loading while running cron.
        // Flush the request buffer
        while(@ob_end_flush());
        flush();
        //Terminate the request
        if (function_exists('fastcgi_finish_request')) fastcgi_finish_request();
        ob_start(); //Keep the image output clean. Hide our dirt.

        // Take in consideration if $user['is_bot'])

        $cron_entity = new cron_entity( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

//$txt = 'Cron enabled in config  ('.$this->session->config['cron_enabled'].')'.PHP_EOL; fwrite($this->myfile, $txt);
        if ( $this->session->config['cron_enabled'] )
        {
            $this->run_cron = true;
            $time_now = time();

            // Any old lock present?
//$txt = 'Cron lock in config  ('.$this->session->config['cron_lock'].')'.PHP_EOL; fwrite($this->myfile, $txt);
            if ( !empty($this->session->config['cron_lock']) )
            {
//$txt = 'Cron NOT locked'.PHP_EOL; fwrite($this->myfile, $txt);
                $cron_time = explode(' ', $this->session->config['cron_lock']);

                // If 10 minutes lock is present we do not call cron
                $time_delay = 60 * 10; // With ten minutes is enough
                if ( $cron_time[0] + $time_delay >= $time_now )
                {
                    $this->run_cron = false;
                }
            }
        }

//$txt = 'Cron run  ('.$this->run_cron.')'.PHP_EOL; fwrite($this->myfile, $txt);
        if ( $this->run_cron )
        {
            //$rows = $this->db->fetchAll('cron', '*', ['periodicity' => 'webcron', 'run' => '1']);
            $filter_select = ['periodicity' => 'webcron', 'run' => '1'];
            $extra_select = 'ORDER BY `id`';
            $rows = $cron_entity->getAll( $filter_select, $extra_select );
//$txt = 'Crons to run:'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($rows, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
            foreach ( $rows as $row )
            {
//$txt = 'Run '.$row['process'].PHP_EOL; fwrite($this->myfile, $txt);
                $this->processAction( $row['id'], $row['process'], $row['last_run'], $row['delaytime'], $row['size'] );
            }
        }
//$txt = '====================== '.__FUNCTION__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//fclose($this->myfile);
    }

    /**
     * @Route("webcronAction $this->processAction", name="webcronAction $this->processAction")
     */
    private function processAction( $id, $process, $last_run, $delaytime, $size )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/cronController_'.__FUNCTION__.'_'.$process.'.txt', 'a+') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' '.$process.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Last_run ('.$last_run.') Delay time ('.$delaytime.') Size ('.$size.')'.PHP_EOL; fwrite($this->myfile, $txt);

        $time_now = time();
//$txt = 'time_now ('.$time_now.')'.PHP_EOL; fwrite($this->myfile, $txt);
        $last_run = \DateTime::createFromFormat('Y-m-d H:i:s', $last_run, new \DateTimeZone($_ENV['time_zone']));
        $last_run = $last_run->getTimestamp();
//$txt = 'Last_run ('.$last_run.') Delay time ('.$delaytime.') Size ('.$size.')'.PHP_EOL; fwrite($this->myfile, $txt);

        if ( $time_now - $delaytime > $last_run )
        {
//$txt = 'Entering process'.PHP_EOL; fwrite($this->myfile, $txt);

            // Lock the cron
            $this->lockCron($process);

            // Process
            $file_name = APP_ROOT_PATH . '/src/controller/cron/'.$process.'Functions.php';
//$txt = 'Process to run ('.$process.') file name ('.$file_name.')'.PHP_EOL; fwrite($this->myfile, $txt);
            if ( file_exists( $file_name) )
            {
                require_once $file_name;
                $class_to_load = '\\src\\controller\\cron\\'.$process.'Functions';
//$txt = 'Class to load ('.$class_to_load.')'.PHP_EOL; fwrite($this->myfile, $txt);
                $processor = new $class_to_load($_ENV, $this->logger, $this->logger_err, $this->startup, $this->db, $this->utils, $this->session, $this->lang, $this->twig);
            }
//$txt = 'Process '.$process.' done.'.PHP_EOL; fwrite($this->myfile, $txt);

            // Set last_run
            $this->setLastRun($id, time());
            // UnLock the cron
            $this->unLockCron();
        }

//$txt = 'cronController '.__FUNCTION__.' '.$process.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     * OS Cron daemon calls this route
     *
     *
     * @Route("/cron/{time}", name="cron_time")
     */
    public function scheduledCronAction( $vars )
    {
//$now = (new \DateTime("now", new \DateTimeZone($_ENV['time_zone'])))->format('Y_m_d_H_i_s');
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/cronController_'.__FUNCTION__.'_'.$vars['time'].'.txt', 'a+') or die('Unable to open file!');
//$txt = 'cronController '.__METHOD__.' start time ('.$vars['time'].') '.$now.' ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

//$txt = 'Cron to run ('.$this->session->config['cron_enabled'].')';
        if ( true )
        {
            $rows = $this->db->fetchAll('cron', '*', ['periodicity' => $vars['time'], 'run' => '1']);
//$txt = 'Crons to process'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($rows, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
            foreach ( $rows as $row )
            {
//fwrite($this->myfile, print_r($row, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                $file_name = APP_ROOT_PATH . '/src/util/'.$row['process'].'Functions.php';
//$txt = 'Process to run ('.$row['process'].') file name ('.$file_name.')'.PHP_EOL; fwrite($this->myfile, $txt);
                if ( file_exists( $file_name) )
                {
                    require_once $file_name;
                    $class_to_load = '\\src\\util\\' . $row['process'] . 'Functions';
//$txt = 'Class to load ('.$class_to_load.')'.PHP_EOL; fwrite($this->myfile, $txt);
                    //$_ENV, $this->logger, $this->startup, $this->db, $this->utils, $this->session, $this->lang, $this->twig
                    $processor = new $class_to_load($_ENV, $this->logger, $this->logger_err, $this->startup, $this->db, $this->utils, $this->session, $this->lang, $this->twig);
                }
            }
        }
//$now_2 = (new \DateTime("now", new \DateTimeZone($_ENV['time_zone'])))->format('Y_m_d_H_i_s');
//$txt = 'cronController '.__METHOD__.' end time '.$vars['time'].' '.$now_2.' ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }

    private function lockCron( $process )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/cronController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = 'cronController '.__FUNCTION__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        // Config not exists in this installation
        // Lock the cron
//        $random = (version_compare(PHP_VERSION, '7.0.0', '>'))? base64_encode( random_bytes(5) ) : uniqid() ;
//
//        $this->db->query("UPDATE config SET config_value = :config_value WHERE config_name = 'cron_lock'");
//        $this->db->bind(':config_value', time() . ' ' . $random . ' '. $process);
//        $this->db->execute();
    }

    private function unLockCron()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/cronController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = 'cronController '.__FUNCTION__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        // Lock the cron
//        $this->db->query("UPDATE config SET config_value = :config_value WHERE config_name = 'cron_lock'");
//        $this->db->bind(':config_value', '');
//        $this->db->execute();
    }

    private function setLastRun( $id )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/cronController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = 'cronController '.__FUNCTION__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $now = new \DateTime('now', new \DateTimeZone($_ENV['time_zone']));
        $this->db->updateArray( 'cron', 'id', $id, ['last_run' => $now->format('Y-m-d H:i:s')]);
    }

    /**
     * @Route("/test/mail", name="test_mail")
     */
    public function mailtestAction()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/cronController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = 'cronController '.__FUNCTION__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $message = '<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional //EN\"><html><head><title>Mail test</title></head><body>MESSAGE_HERE</body></html>';
        $mail = new PHPMailer(true);

//fwrite($this->myfile, print_r(get_declared_classes(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        try {
            //Server settings//Enable SMTP debugging
            //// SMTP::DEBUG_OFF = off (for production use)
            //// SMTP::DEBUG_CLIENT = client messages
            //// SMTP::DEBUG_SERVER = client and server messages
//$txt = 'Debug verbose  ('.SMTP::DEBUG_SERVER.')'.PHP_EOL; fwrite($this->myfile, $txt);
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
            $mail->isSMTP();                                            // Send using SMTP                        //Tell PHPMailer to use SMTP
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            $mail->Host       = $_ENV['mail_host'];     // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                   // Enable SMTP authentication
            $mail->Username   = $_ENV['mail_username']; // SMTP username
            $mail->Password   = $_ENV['mail_password']; // SMTP password
            $mail->Port       = '465';//$_ENV['mail_port'];     // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
            //$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;

            //Recipients
            $mail->setFrom($this->session->config['email_system_address'], $this->session->config['email_system_name']);

            $mail->addAddress('carlos@openges.com', 'Carlos');     // Add a recipient
            //$mail->addAddress('ellen@example.com');               // Name is optional
            $mail->addReplyTo($this->session->config['email_system_address'], $this->session->config['email_system_name']);
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('bcc@example.com');

            // Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Here is the subject';
            $message_body = 'This is the HTML message body <b>in bold!</b><br /><img id="logo" src="web/bundles/framework/images/mail/default/mail_header.png">';
            //$message_body = 'This is the HTML message body <b>in bold!</b><br /><img id="logo" src="web/bundles/framework/images/mail/default/logo_hor.png">';
            $body = str_replace('MESSAGE_HERE', $message_body, $message);
            $mail->msgHTML($body, APP_ROOT_PATH);
            //$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
//$txt = 'Message sent '.PHP_EOL; fwrite($this->myfile, $txt);
//    echo 'Message has been sent';

        } catch (Exception $e) {
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error('Mail Test Error -- Message not sent.');
            $this->logger_err->error('==================================================');
            $this->logger_err->error('Host (' . $mail->Host . ')');
            $this->logger_err->error('Port (' . $mail->Port . ')');
            $this->logger_err->error('Username (' . $mail->Username . ')');
            $this->logger_err->error('Password (' . $mail->Password . ')');
            $this->logger_err->error('Mailer Error: ' . $mail->ErrorInfo);
            $this->logger_err->error('==================================================');
            $this->logger_err->error('*************************************************************************');
//$txt = 'Message NOT sent!' . PHP_EOL;
//$txt .= '=================================================================================' . PHP_EOL;
//$txt .= 'Host (' . $mail->Host . ')' . PHP_EOL;
//$txt .= 'Port (' . $mail->Port . ')' . PHP_EOL;
//$txt .= 'Username (' . $mail->Username . ')' . PHP_EOL;
//$txt .= 'Password (' . $mail->Password . ')' . PHP_EOL;
//$txt .= 'Mailer Error: ' . $mail->ErrorInfo . PHP_EOL;
//$txt .= '=================================================================================' . PHP_EOL;fwrite($this->myfile, $txt);

//    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
        /*
                if ( !$mail->send() ) {
                    echo 'Mailer Error: '. $mail->ErrorInfo;
                } else {
                    echo 'Message sent!';
                }
        */
//$txt = 'cronController '.__FUNCTION__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//echo '<br>Hasta aqui.'; die();
        return true;
    }
}
