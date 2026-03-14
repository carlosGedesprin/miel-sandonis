<?php

namespace src\controller;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

use \src\controller\cron\mailFunctions;
use src\controller\entity\mailQueueController;
use src\controller\entity\langTextController;

use DateTime;
use DateTimeZone;

class testMailController extends baseViewController
{
    private $mailQueue;

    /**
     * Test mail templates
     *
     */
    public function generateMailAction()
    {
$txt = '====================== ' . __METHOD__ . ' start ===============================================================' . PHP_EOL; fwrite($this->myfile, $txt);
        $mailQueue = new mailQueueController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $mailQueue->setToName( 'Javier Quintero' );
        $mailQueue->setLocale( 'es' );
        $mailQueue->setToAddress( 'carlos@openges.com' );

        $mailQueue->setTemplate( 'custom_message' );
        $mailQueue->setProcess(__METHOD__ );
        $mailQueue->setSubject( 'Accede Me: mensaje TEST' );
        $mailQueue->setPreheader( 'Accede Me: mensaje TEST' );

        $mailQueue->addAssignVar('message', 'Mensaje de test<br />fin.' );

        $mailQueue->persist();// Process
                                            //$env, $logger, $logger_err, $startup, $db, $utils, $session, $lang, $twig
        $mail_functions = new mailFunctions( $this->env, $this->logger, $this->logger_err, $this->startup, $this->db, $this->utils, $this->session, $this->lang, $this->twig );
$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }
    /**
     * Test mail templates
     *
     */
    public function mailTestAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH . '/var/logs/testController_' . __FUNCTION__ . '.txt', 'w') or die('Unable to open file!');
//$txt = 'testController ' . __METHOD__ . ' start ===============================================================' . PHP_EOL; fwrite($this->myfile, $txt);

        $this->mailQueue = new mailQueueController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $this->mailQueue->setToName( 'Silvia' );
        $this->mailQueue->setLocale( 'es' );
        $this->mailQueue->setToAddress( 'silvia@accedeme.com' );
        $this->mailQueue->setProcess(__METHOD__ );

        $template = $vars['template'];
        $this->mailQueue->setTemplate( $template );

//        $mailQueue->setSubject( 'Test mail '.$template );
//        $mailQueue->setPreheader( 'Test mail '.$template );
        $this->mailQueue->setSubject( langTextController::getLangText( $this->utils, 'es', 'MAIL_NEWSLETTER_WELCOME') );
        $this->mailQueue->setPreheader( langTextController::getLangText( $this->utils, 'es', 'MAIL_NEWSLETTER_WELCOME') );

        $this->$template();

        $this->mailQueue->persist();

//$txt = 'testController '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return 'Done '.$template;
    }
    /**
     * account_lacks_tax_data
     *
     */
    public function account_lacks_tax_data()
    {
        $this->mailQueue->addAssignVar( 'card_digits', '1234' );
        $this->mailQueue->addAssignVar( 'renew_link', $this->startup->getUrlApp().'/payments/renew_card/popo' );
    }
    /**
     * account_renew_bad_card
     *
     */
    public function account_renew_bad_card()
    {
        $this->mailQueue->addAssignVar( 'card_digits', '1234' );
        $this->mailQueue->addAssignVar( 'renew_link', $this->startup->getUrlApp().'/payments/renew_card/popo' );
    }
    /**
     * account_renew_card_exp
     *
     */
    public function account_renew_card_exp()
    {
        $this->mailQueue->addAssignVar( 'card_digits', '1234' );
        $this->mailQueue->addAssignVar( 'renew_link', $this->startup->getUrlApp().'/payments/renew_card/popo' );
    }
    /**
     * certification_link
     *
     */
    public function certification_link()
    {
        $this->mailQueue->addAssignVar( 'certification_image_iaw', "" );
        $this->mailQueue->addAssignVar( 'certification_image_internal', "/web/bundles/framework/images/certificates/es/certificate_partial.png" );
        $this->mailQueue->addAssignVar( 'certification_link_iaw', "certification_data['link']['iaw']" );
        $this->mailQueue->addAssignVar( 'certification_link_internal', "certification_data['link']['internal']" );
        $this->mailQueue->addAssignVar( 'certification_code_iaw', "certification_data['code']['iaw']" );
        $this->mailQueue->addAssignVar( 'certification_code_internal', "certification_data['code']['internal']" );
        $this->mailQueue->addAssignVar( 'login_link', '/login' );
    }
    /**
     * certification_renew_today
     *
     */
    public function certification_renew_today()
    {
        $this->mailQueue->addAssignVar('renew_link', $this->startup->getUrlApp().'/payments/renew_certification/popo' );
    }
    /**
     * check_accessibility_link
     *
     */
    public function check_accessibility_link()
    {
        $this->mailQueue->addAssignVar('domain', $_ENV['domain'] );
        $this->mailQueue->addAssignVar('report_link', $this->startup->getUrlApp().'/show-report/popo' );
    }
    /**
     * check_accessibility_report
     *
     * NOT Used
     *
     */
    public function check_accessibility_report()
    {
        $this->mailQueue->addAssignVar('domain', $_ENV['domain'] );
        $this->mailQueue->addAssignVar('report_link', $this->startup->getUrlApp().'/show-report/popo' );
    }
    /**
     * check_accessibility_customer_service
     *
     */
    public function check_accessibility_customer_service()
    {
        $this->mailQueue->addAssignVar('report_type', 'Free' );
        $this->mailQueue->addAssignVar('report_level', '1' );
        $this->mailQueue->addAssignVar('contact_domain', 'accedeme' );
        $this->mailQueue->addAssignVar('contact_name', 'Joselito' );
        $this->mailQueue->addAssignVar('contact_email', 'popo@popo.es' );
        $this->mailQueue->addAssignVar('contact_phone', '55555555555' );
    }
    /**
     * check_accessibility_thanks
     *
     * NOT Used
     *
     */
    public function check_accessibility_thanks()
    {
    }
    /**
     * custom_message
     *
     */
    public function custom_message()
    {
        $this->mailQueue->addAssignVar('message', 'Bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla' );
    }
    /**
     * newsletter_mail
     *
     */
    public function newsletter_mail()
    {
    }
    /**
     * newsletter_welcome
     *
     */
    public function newsletter_welcome()
    {
    }
    /**
     * newsletter_welcome_sender
     *
     */
    public function newsletter_welcome_sender()
    {
    }
    /**
     * certification_pay
     *
     */
    public function certification_pay()
    {
        $this->mailQueue->addAssignVar('domain', 'mision-en-cuba.com' );
        $this->mailQueue->addAssignVar('pay_link', $this->startup->getUrlApp().'/payments/choose_quote_product/popo' );
    }
    /**
     * wcag_report_pay
     *
     */
    public function wcag_report_pay()
    {
        $this->mailQueue->addAssignVar('domain', 'mision-en-cuba.com' );
        $this->mailQueue->addAssignVar('pay_link', $this->startup->getUrlApp().'/payments/choose_quote_product/popo' );
    }
    /**
     * payment_need_auth
     *
     */
    public function payment_need_auth()
    {
        $this->mailQueue->addAssignVar('auth_link', $this->startup->getUrlApp().'/payments/choose_product/popo' );
    }
    /**
     * payment_failed
     *
     */
    public function payment_failed()
    {
    }
    /**
     * payment_successful
     *
     */
    public function payment_successful()
    {
        $this->mailQueue->addAssignVar('currency', $this->session->config['web_currency']);

        $total_f = floatval( '4900' ) / 100;
        $total_real = number_format($total_f, 2, ',', '.');
        $this->mailQueue->addAssignVar('total', $total_real);
    }
    /**
     * phone_contact
     *
     */
    public function phone_contact()
    {
        $this->mailQueue->addAssignVar('contact_name', 'Joselito');
        $this->mailQueue->addAssignVar('company_name', 'Jamones JJJ');
        $this->mailQueue->addAssignVar('company_phone', '5555555555');
        $this->mailQueue->addAssignVar('preferred_schedule', 'Tempranito');
        $this->mailQueue->addAssignVar('remote_ip', '127.0.0.1');
    }
    /**
     * prestashop_website
     *
     */
    public function prestashop_website()
    {
        $this->mailQueue->addAssignVar('domain', 'Accedeme');
        $this->mailQueue->addAssignVar('failed', true);
    }
    /**
     * widget_register_customer_service
     *
     */
    public function widget_register_customer_service()
    {
        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']));

        $this->mailQueue->addAssignVar( 'account_id', '33' );
        $this->mailQueue->addAssignVar( 'account_name', 'Joselito JJJ' );
        $this->mailQueue->addAssignVar( 'account_key', 'liuh987dh9y9hdo2h981dij09ud 0j9dpo' );
        $this->mailQueue->addAssignVar( 'account_mail', 'popo@popo.com' );

        $this->mailQueue->addAssignVar( 'user_id', '18' );
        $this->mailQueue->addAssignVar( 'user_key', 'loije09j09j9ij029j9j20j0j9j2dok2' );
        $this->mailQueue->addAssignVar( 'user_mail', 'pipo@popo.com' );
        $this->mailQueue->addAssignVar( 'user_name', 'Javier' );

        $this->mailQueue->addAssignVar( 'widget_key', '09uje9ij2d0pj290j09jdp0j920jj0j' );
        $this->mailQueue->addAssignVar( 'widget_domain', 'popo.com' );
        $this->mailQueue->addAssignVar( 'widget_plan', '16' );
        $this->mailQueue->addAssignVar( 'widget_reg', $now->format('Y-m-d H:i:s') );
    }
    /**
     * settlement_commissions_pdf
     *
     */
    public function settlement_commissions_pdf()
    {
        $pdf = '';
        $this->mailQueue->addAttached( 'settlement', $pdf );
    }
    /**
     * user_activate
     *
     */
    public function user_activate()
    {
        $this->mailQueue->addAssignVar('confirmation_link', $this->startup->getUrlApp().'/activate-user/98hc9whc98wpijhn' );
    }
    /**
     * user_de_activated
     *
     */
    public function user_de_activated()
    {
        $this->mailQueue->addAssignVar( 'token', '98y8he98h98' );
    }
    /**
     * user_mail_changed
     *
     */
    public function user_mail_changed()
    {
        $this->mailQueue->addAssignVar( 'token', '98y8he98h98' );
    }
    /**
     * user_pass_change
     *
     */
    public function user_pass_change()
    {
        $this->mailQueue->addAssignVar( 'token', '98h28h98po' );
        $this->mailQueue->addAssignVar( 'confirmation_link', $this->startup->getUrlApp().'/change-password/popo@popo.com/90890ihje9p89p8' );
    }
    /**
     * user_pass_changed
     *
     */
    public function user_pass_changed()
    {
        $this->mailQueue->addAssignVar( 'token', '98h28h98po' );
        $this->mailQueue->addAssignVar( 'confirmation_link', $this->startup->getUrlApp().'/change-password/popo@popo.com/90890ihje9p89p8' );
    }
    /**
     * user_unsubscribe
     *
     */
    public function user_unsubscribe()
    {
        $this->mailQueue->addAssignVar( 'token', '98h28h98po' );
        $this->mailQueue->addAssignVar( 'confirmation_link', $this->startup->getUrlApp().'/change-password/popo@popo.com/90890ihje9p89p8' );
    }
    /**
     * user_welcome
     *
     */
    public function user_welcome()
    {
        $this->mailQueue->addAssignVar( 'user_email', 'silvia@popo.es' );
    }
    /**
     * web_contact
     *
     */
    public function web_contact()
    {
        $this->mailQueue->addAssignVar('contact_name', 'Joselito' );
        $this->mailQueue->addAssignVar('contact_surname', 'Ja Ja' );
        $this->mailQueue->addAssignVar('contact_email', 'popo@popo.com' );
        $this->mailQueue->addAssignVar('contact_message', 'Gracias por todo' );
        $this->mailQueue->addAssignVar('contact_web_name', $this->session->config['web_name'] );
    }
    /**
     * web_contact_sender
     *
     */
    public function web_contact_sender()
    {
        $this->mailQueue->addAssignVar('contact_name', 'Joselito' );
        $this->mailQueue->addAssignVar('contact_surname', 'Ja Ja' );
        $this->mailQueue->addAssignVar('contact_email', 'popo@popo.com' );
        $this->mailQueue->addAssignVar('contact_message', 'Gracias por todo' );
        $this->mailQueue->addAssignVar('contact_web_name', $this->session->config['web_name'] );
    }
    /**
     * web_partner_contact
     *
     */
    public function web_partner_contact()
    {
        $this->mailQueue->addAssignVar('contact_name', 'Joselito' );
        $this->mailQueue->addAssignVar('contact_company', 'Jamones JJJ' );
        $this->mailQueue->addAssignVar('contact_domain', 'jamosnes@popo.com' );
        $this->mailQueue->addAssignVar('contact_email', 'popo@popo.com' );
        $this->mailQueue->addAssignVar('contact_phone', '5555555' );
        $this->mailQueue->addAssignVar('contact_message', 'Gracias por todo' );
        $this->mailQueue->addAssignVar('contact_web_name', $this->session->config['web_name'] );
    }
    /**
     * web_partner_contact_sender
     *
     */
    public function web_partner_contact_sender()
    {
        $this->mailQueue->addAssignVar('contact_name', 'Joselito' );
        $this->mailQueue->addAssignVar('contact_company', 'Jamones JJJ' );
        $this->mailQueue->addAssignVar('contact_domain', 'jamosnes@popo.com' );
        $this->mailQueue->addAssignVar('contact_email', 'popo@popo.com' );
        $this->mailQueue->addAssignVar('contact_phone', '5555555' );
        $this->mailQueue->addAssignVar('contact_message', 'Gracias por todo' );
        $this->mailQueue->addAssignVar('contact_web_name', $this->session->config['web_name'] );
    }
    /**
     * web_partner_register
     *
     */
    public function web_partner_register()
    {
        $this->mailQueue->addAssignVar( 'login_link', $_ENV['protocol'].'://'.$_ENV['domain'].'/login' );

        $this->mailQueue->addAssignVar('partner_name', 'Joselito' );
        $this->mailQueue->addAssignVar('partner_company', 'Jamones JJJ' );
        $this->mailQueue->addAssignVar('partner_domain', 'jamosnes-jjj.com' );
        $this->mailQueue->addAssignVar('partner_email', 'popo@popo.com' );
        $this->mailQueue->addAssignVar('partner_phone', '5555555' );
    }
    /**
     * web_partner_register_info
     *
     */
    public function web_partner_register_info()
    {
        $this->mailQueue->addAssignVar('partner_name', 'Joselito' );
        $this->mailQueue->addAssignVar('partner_company', 'Jamones JJJ' );
        $this->mailQueue->addAssignVar('partner_domain', 'jamosnes-jjj.com' );
        $this->mailQueue->addAssignVar('partner_email', 'popo@popo.com' );
        $this->mailQueue->addAssignVar('partner_phone', '5555555' );
    }
    /**
     * widget_mid_demo
     *
     */
    public function widget_mid_demo()
    {
    }
    /**
     * widget_penultimate_day_demo
     *
     */
    public function widget_penultimate_day_demo()
    {
    }
    /**
     * widget_renew_overdue
     *
     */
    public function widget_renew_overdue()
    {
        $this->mailQueue->addAssignVar('domain_name', 'popo.com' );
    }
    /**
     * widget_renew_overdue_grace
     *
     */
    public function widget_renew_overdue_grace()
    {
        $this->mailQueue->addAssignVar('domain_name', 'popo.com' );
    }
    /**
     * website_renew_tomorrow
     *
     */
    public function website_renew_tomorrow()
    {
        $this->mailQueue->addAssignVar('domain_name', 'popo.com' );
    }
    /**
     * widget_last_day_demo
     *
     */
    public function widget_last_day_demo()
    {
        $this->mailQueue->addAssignVar('domain', 'popo.com' );
        $contract_link = $this->startup->getUrlApp().'/payments/choose_quote_product/98huhx9oh9o8xho9q8ho9hqo9';
        $this->mailQueue->addAssignVar('contract_link', $contract_link );
    }
    /**
     * widget_renew_today
     *
     */
    public function widget_renew_today()
    {
        $this->mailQueue->addAssignVar('domain_name', 'popo.com' );
        $renew_link = $this->startup->getUrlApp().'/payments/pay_a_renew_quote/1/098he90oi2he982hd82h9d8';
        $this->mailQueue->addAssignVar('renew_link', $renew_link );
    }
    /**
     * widget_instructions
     *
     */
    public function widget_instructions()
    {
        $this->mailQueue->addAssignVar( 'login_link', $this->startup->getUrlApp().'/login' );
    }
    /**
     * wordpress_website
     *
     */
    public function wordpress_website()
    {
        $this->mailQueue->addAssignVar('domain', 'popo.com' );
        $this->mailQueue->addAssignVar('failed', true);
    }
    /**
     * kit_digital
     *
     */
    public function kit_digital()
    {
        $this->mailQueue->setSubject( 'Accesibilidad Web del Kit Digital en 15 minutos' );
        $this->mailQueue->setPreheader( 'Accesibilidad Web del Kit Digital en 15 minutos' );
    }
    /**
     * rusticae
     *
     */
    public function rusticae()
    {
    }

    /**
     * @Route("/test/php_mailer_test", name="test_php_mailer_test")
     */
    public function phpMailerTestAction()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/cronController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
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
            $mail->Host       = $this->session->config['mail_host'];     // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                   // Enable SMTP authentication
            $mail->Username   = $this->session->config['mail_username']; // SMTP username
            $mail->Password   = $this->session->config['mail_password']; // SMTP password
            $mail->Port       = $this->session->config['mail_port'];//$_ENV['mail_port'];     // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
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
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//echo '<br>Hasta aqui.'; die();
        return true;
    }
}