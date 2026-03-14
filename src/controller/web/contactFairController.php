<?php

namespace src\controller\web;

use \src\controller\baseViewController;

use src\controller\entity\leadFairController;
use \src\controller\entity\leadMarketController;

use \src\controller\entity\mailQueueController;
use src\controller\entity\spammerController;

use DateTime;
use DateTimeZone;

class contactFairController extends baseViewController
{
    private $data;

    /**
     * @Route("/contact-feria", name="contact_feria")
     */
    public function contactAction()
    {
        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']));
$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/web_contactFairController_'.__FUNCTION__.'.txt', 'a+') or die('Unable to open file!');
$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);

        $spammer = new spammerController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $lead = new leadFairController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $lead_market = new leadMarketController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $mailQueue = new mailQueueController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $this->data['group'] = $this->utils->request_var( 'group', '', 'ALL');
        $this->data['name'] = $this->utils->request_var( 'name', '', 'ALL', true);
        $this->data['email'] = $this->utils->request_var( 'email', '', 'ALL', true);
        $this->data['phone'] = $this->utils->request_var( 'phone', '', 'ALL', true);
        $this->data['phone_mobile'] = $this->utils->request_var( 'phone_mobile', '', 'ALL', true);
        $this->data['locale'] = $this->utils->request_var( 'locale', '', 'ALL');
        $this->data['company'] = $this->utils->request_var( 'company', '', 'ALL', true);
        $this->data['position'] = $this->utils->request_var( 'position', '', 'ALL', true);
        $this->data['linkedin'] = $this->utils->request_var( 'linkedin', '', 'ALL', true);
        $this->data['instagram'] = $this->utils->request_var( 'instagram', '', 'ALL', true);
        $this->data['twitter'] = $this->utils->request_var( 'twitter', '', 'ALL', true);
        $this->data['market'] = $this->utils->request_var( 'market', '', 'ALL');
        $this->data['origin'] = $this->utils->request_var( 'origin', '', 'ALL');
        $this->data['lead_market_options'] = '';
        $this->data['market_other'] = $this->utils->request_var( 'market_other', '', 'ALL', true);
        $this->data['notes'] = $this->utils->request_var( 'notes', '', 'ALL', true);
        $this->data['terms'] = $this->utils->request_var( 'terms', '1', 'ALL');
        $this->data['submit'] = (isset($_POST['btn_submit'])) ? true : false;

        $this->data['newsletter_accept'] = $this->utils->request_var( 'newsletter_accept', '1', 'ALL');

//$txt = 'POST ==========>'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
$txt = 'DATA ==========>'.PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r($this->data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $error_ajax = array();

        $is_spamm = false;

        if ( $this->data['submit'] )
        {
//$txt = 'Submit ========>'.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
$folder = ( $_ENV['env_env'] == 'dev' )? '' : '/contacts';
$contactus = fopen(APP_ROOT_PATH.'/var/logs'.$folder.'/contactController_contactus_'.$now->format('Y_m_d_H_i_s').'.txt', 'a+') or die('Unable to open file!');
$txt = '====================== '.__METHOD__.' start '.$now->format('d-m-Y H:i:s').' ======================================'.PHP_EOL; fwrite($contactus, $txt);
$txt = 'Data ==========>'.PHP_EOL; fwrite($contactus, $txt);
fwrite($contactus, print_r($this->data, TRUE)); $txt = PHP_EOL; fwrite($contactus, $txt);
if ( isset( $_POST['cf-turnstile-response'] ) )
{
    $txt = 'Turnstile response in POST ==========>'.PHP_EOL; fwrite($contactus, $txt);
    fwrite($contactus, print_r($_POST['cf-turnstile-response'], TRUE)); $txt = PHP_EOL; fwrite($contactus, $txt);
}
$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($contactus, $txt);
fclose($contactus);

            /*------- Cloudflare Turnstile spamm detector start ---------- */
            if ( $_ENV['env_env'] == 'prod' )
            {
                $turnstile_secret     = $_ENV['turnstile_secret_key'];
                $turnstile_response   = ( isset( $_POST['cf-turnstile-response'] ) )? $_POST['cf-turnstile-response'] : '0';
//$txt = 'Turnstile response in POST ==========>'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($turnstile_response, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                $url                  = "https://challenges.cloudflare.com/turnstile/v0/siteverify";
                $post_fields          = "secret=$turnstile_secret&response=$turnstile_response";

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
                $response = curl_exec($ch);
                curl_close($ch);

                $response_data = json_decode($response);
//$txt = 'Turnstile data ==========>'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($response_data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

                if ( $response_data->success != 1 )
                {
//$txt = 'Is spamm ==========> '.$this->data['phone'].PHP_EOL; fwrite($this->myfile, $txt);
                    $is_spamm = true;
                    $spammer->setId('');
                    $spammer->setName( $this->data['name'] );
                    $spammer->persist();
                    $spammer->setId('');
                    //$spammer->setName( $this->data['company'] );
                    $spammer->persist();
                    $spammer->setName( NULL );

                    $spammer->setId('');
                    $spammer->setEmail( $this->data['email'] );

                    if ( isset( $_SERVER['REMOTE_ADDR'] ) && !empty( $_SERVER['REMOTE_ADDR'] ) )
                    {
                        $spammer->setRemoteAddr($_SERVER['REMOTE_ADDR']);
                    }

                    if ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) && !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) )
                    {
                        $spammer->setHttpXForwardedFor($_SERVER['HTTP_X_FORWARDED_FOR']);
                    }

                    $spammer->setText( '' );
                    $spammer->persist();
                    //$error[] = $this->lang['ERR_WEBSITE_CONTACT_SPAMM'];
                    //header("Location: /login.php?error=recaptcha");
                    //exit;
                }
            }
            /*------- Cloudflare Turnstile spamm detector end ---------- */

            if ( empty( $this->data['name'] ) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['name'],
                    'msg' => $this->lang['ERR_WEBSITE_CONTACT_NAME_NEEDED'],
                );
            }

            $match = '/^[A-Za-z0-9-_.+%]+@[A-Za-z0-9-.]+\.[A-Za-z]{2,20}$/';
            if ( empty( $this->data['email'] ) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['email'],
                    'msg' => $this->lang['ERR_WEBSITE_CONTACT_EMAIL_NEEDED'],
                );
            }
            else
            {
                if ( !preg_match($match, $this->data['email']) )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['email'],
                        'msg' => $this->lang['ERR_WEBSITE_CONTACT_EMAIL_BAD'],
                    );
                }
            }
            if ( empty( $this->data['phone'] ) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['phone'],
                    'msg' => $this->lang['ERR_WEBSITE_CONTACT_PHONE_NEEDED'],
                );
            }

            if ( $this->data['terms'] != '1' )
            {
                $error_ajax[] = array (
                    'dom_object' => ['terms'],
                    'msg' => $this->lang['ERR_WEBSITE_CONTACT_TERMS_MUST_AGREED'],
                );
            }

            $is_spamm = false;
            if ( $spammer->getSpammerbyName( $this->data['name'] ) )
            {
                $is_spamm = true;
            }
            if ( $spammer->getSpammerbyEmail( $this->data['email'] ) )
            {
                $is_spamm = true;
            }
            /*
            if ( $is_spamm )
            {
                    $error[] = $this->lang['ERR_FUNCTION_ILLEGAL'];
            }
            */
            if ( $is_spamm )
            {
$folder = ( $_ENV['env_env'] == 'dev' )? '' : '/contacts';
$contactus_spam = fopen(APP_ROOT_PATH.'/var/logs'.$folder.'/contactController_contactus_spam_'.$now->format('Y_m_d_H_i_s').'.txt', 'a+') or die('Unable to open file!');
$txt = '====================== '.__METHOD__.' start '.$now->format('d-m-Y H:i:s').' ======================================'.PHP_EOL; fwrite($contactus_spam, $txt);
$txt = '< ========== SPAM ==========>'.PHP_EOL; fwrite($contactus_spam, $txt);
                $ip = ( isset( $_SERVER['REMOTE_ADDR'] ) )? $_SERVER['REMOTE_ADDR'] : '';
                $ip .= ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) )? ' | '.$_SERVER['HTTP_X_FORWARDED_FOR'] : '';
                $txt = 'Contact '.$this->data['name'].' '.$this->data['email'].' '.$this->data['notes'].' '.$ip.PHP_EOL; fwrite($contactus_spam, $txt);
$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($contactus_spam, $txt);
fclose($contactus_spam);

                // Send continue to be displayed
                $response = array();
                $response['status'] = 'CONTINUE';
                $response['action'] = '/'.$this->lang['WEB_MENU_CONTACT_THANKS_LINK'];
                echo json_encode($response);
                exit();
                //header('Location: /'.$this->lang['WEB_MENU_CONTACT_THANKS_LINK']);
                //exit;
            }
//$txt = 'Errors ==========>'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($error_ajax, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

            if ( !sizeof( $error_ajax ) )
            {
//$txt = 'No errors ========>'.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);

                if ( !$lead->getRegbyEmail( $this->data['email'] ) )
                {
                    $lead->setDateReg( $now );
                    $lead->setGroup($this->data['group']);
                    $lead->setName($this->data['name']);
                    $lead->setEmail($this->data['email']);
                    $lead->setPhone($this->data['phone']);
                    $lead->setPhoneMobile($this->data['phone_mobile']);
                    $lead->setLocale($this->data['locale']);
                    $lead->setCompany($this->data['company']);
                    $lead->setPosition($this->data['position']);
                    $lead->setLinkedin($this->data['linkedin']);
                    $lead->setInstagram($this->data['instagram']);
                    $lead->setTwitter($this->data['twitter']);
                    if ( !empty( $this->data['market'] ) )
                    {
                        $lead_market->getRegbyId( $this->data['market'] );
                        $lead->setMarket($lead_market->getName());
                    }
                    $lead->setOrigin($this->data['origin']);
                    /*
                     *
                            'address'      => NULL,
                            'post_code'    => NULL,
                            'country'      => NULL,
                            'region'       => NULL,
                            'city'         => NULL,
                            'alt_city'     => NULL,
                            'send_emails'  => NULL,
                            'notes'        => NULL,
                            'active'       => '1',
                     */
                    $lead->setSendEmails('1');
                    $lead->setActive('1');
                    $lead->setNotes($this->data['notes']);
                    $lead->persist();

                    $lead->setLeadKey(md5($lead->getId() . $lead->getName()));
                    $lead->persist();
                }

                $mailQueue->setToName( $this->session->config['web_name'] );
                $mailQueue->setToAddress( $this->session->config['customer_service_email_address'] );

                $mailQueue->setTemplate( 'web_contact_fair_customer_service' );

                //$mailQueue->setSubject( $this->lang['MAIL_CONTACT_MAIL_SUBJECT'] );
                $mailQueue->setSubject( 'Visita Technology Fair 2025' ); # Contacto visitante de Technology Show 2025 #
//                mailQueue->setPreheader( langTextController::getLangText( $this->utils, $this->session->getLanguageCode2a(), 'MAIL_CONTACT_MAIL_PREHEADER' ) );
                $mailQueue->setSubject( $this->lang['MAIL_CONTACT_MAIL_PREHEADER'] );

                $mailQueue->addAssignVar('contact_name', $this->data['name'] );
                $mailQueue->addAssignVar('contact_email', $this->data['email'] );
                $mailQueue->addAssignVar('contact_phone', $this->data['phone'] );
                $mailQueue->addAssignVar('contact_notes', $this->data['notes'] );
                $mailQueue->addAssignVar('contact_web_name', $this->session->config['web_name'] );
//$txt = 'Mail Queue ==========>'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($mailQueue->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

                if ( !$is_spamm ) $mailQueue->persist();
//$txt = '----------------------------- Mail sent to customer service ---------------------------------------'.PHP_EOL; fwrite($this->myfile, $txt);

                $mailQueue->setId('');

                $mailQueue->setToName( $this->data['name'] );
                $mailQueue->setToAddress( $this->data['email'] );

                $mailQueue->setTemplate( 'web_contact_fair_visitor' );

                //$mailQueue->setSubject( sprintf( $this->lang['MAIL_CONTACT_MAIL_SUBJECT_SENDER'], $this->session->config['web_name'] ) ) ;
                $mailQueue->setSubject( 'Agradecimiento visita Technology Fair 2025' ) ; # ¡Gracias por tu visita! Altira Automations en Technology Show 2025 #
                //$mailQueue->setPreheader( sprintf( $this->lang['MAIL_CONTACT_MAIL_PREHEADER_SENDER'], $this->session->config['web_name'] ) ) ;

                $mailQueue->addImage('image_signature', 'logo_with_text_ver.png');

                if ( !$is_spamm ) $mailQueue->persist();
//$txt = '----------------------------- Mail sent to customer ---------------------------------------'.PHP_EOL; fwrite($this->myfile, $txt);

                // Send success to be displayed
                $response = array();
                $response['status'] = 'OK';
                $response['action'] = '/contacto-feria';
//$txt = 'Success to display -> /'.$this->lang['WEB_MENU_CONTACT_THANKS_LINK'].PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
//fclose($this->myfile);
                echo json_encode($response);
                exit();
            }
            else
            {
                // Errors found

                $response = array();
                $response['status'] = 'KO';
                foreach( $error_ajax as $key => $value )
                {
                    $response['errors'][] = $value;
                }
//$txt = 'Errors to display'.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Errors =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($response['errors'], TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
//fclose($this->myfile);
                echo json_encode($response);
                exit();
            }
        }

        // Lead market select options list
        $filter_select = ['active' => '1'];
        $extra_select = 'ORDER BY `name`';
        $rows = $lead_market->getAll( $filter_select, $extra_select );
        $this->data['lead_market_options'] .= '<option value="">'.$this->lang['SELECT'].'</option>';
        $this->data['lead_market_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        foreach ( $rows as $row )
        {
            $this->data['lead_market_options'] .= '<option value="'.$row['id'].'"'.(($this->data['market'] == $row['id'])? ' selected="selected" ' : '').'>'.$row['name'].'</option>';
        }
//$txt = 'Errors before display ==========>'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($error_ajax, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
fclose($this->myfile);
        return $this->twig->render('web/'.$this->session->config['website_skin'].'/contact_fair.html.twig', array(
            'data' => $this->data,
            'errors' => $error_ajax,
        ));
    }

    /**
     * @Route("/contact-thank-you", name="contact-thank-you")
     */
    public function contactThanksAction()
    {
$folder = ( $_ENV['env_env'] == 'dev' )? '' : '/contacts';
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs'.$folder.'/contactController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);

//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
        return $this->twig->render('web/'.$this->session->config['website_skin'].'/contactus_thanks.html.twig', array(
            'data' => $this->data,
        ));
    }
}