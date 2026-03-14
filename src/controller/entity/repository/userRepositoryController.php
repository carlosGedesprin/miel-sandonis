<?php

namespace src\controller\entity\repository;

use \src\controller\entity\accountController;
use src\controller\entity\langTextController;
use src\controller\entity\mailQueueController;
use src\controller\entity\userController;

use DateTime;
use DateTimeZone;
use DateInterval;

/**
 * Trait user
 * @package entity
 */
trait userRepositoryController
{
    public function createUser( $account_id, $name, $email, $password, $locale=NULL )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $this->setAccount( $account_id );
        $this->setEmail( $email );
        $this->setPassword( password_hash( $password, PASSWORD_BCRYPT, $this->crypt_options ) );
        $this->setName( $name );
        $this->setLocale( ( $locale == NULL )? $this->session->getLanguageCode2a() : $locale );
        $this->setShowToStaff( '1' );

//$txt = 'Verify account ========> ('.$this->session->config['verify_account'].')'.PHP_EOL.PHP_EOL; fwrite($this->$this->myfile, $txt);
        if ( $this->session->config['verify_account'] )
        {
            $random = base64_encode( random_bytes(5) );
            $random = str_replace( '/' , '$' , $random );
            $this->setActivationKey( $random );
            $this->setActive( '0' );
        }
        else
        {
            $this->setActivationKey( '' );
            $this->setActive( '1' );
        }
        $this->persist();

        $this->setUserKey( md5( $this->getId().$this->getEmail() ) );
        $this->persist();
//$txt = 'User ========> '.$this->>getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->getReg(), TRUE));
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     *
     * Get User's Account Group
     */
    public function getGroup()
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        require_once( APP_ROOT_PATH.'/src/controller/baseController.php');
        require_once( APP_ROOT_PATH.'/src/controller/entity/accountController.php');
        $account = new accountController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $account->getRegbyId( $this->reg['account'] );

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $account->getGroup();
    }

    /**
     *
     * Get user's password
     *
     */
    public function getUserPassword( $id )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Id ==========> ('.$id.')'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $this->db->fetchField( $this->table, 'password', ['id' => $id]);
    }

    /**
     *
     * Sends user welcome email
     *
     * @return void
     */
    public function send_welcome_email()
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'User ================== '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']));

        $minutes_to_add = 0;

        $mailQueue = new mailQueueController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $send = $now->add(new DateInterval('PT' . $minutes_to_add . 'M'));
        $mailQueue->setSend( $send );

        $mailQueue->setToName( $this->getName() );
        $mailQueue->setLocale( $this->getLocale() );
        $mailQueue->setToAddress( $this->getEmail() );

        $mailQueue->setTemplate('user_welcome');
        $mailQueue->setProcess(__METHOD__ );

        $mailQueue->setSubject( sprintf( langTextController::getLangText( $this->utils, $this->getLocale(), 'MAIL_WELCOME_ACCOUNT_SUBJECT' ), $this->session->config['web_name'] ) );
//$txt = 'Subject '.$mailQueue->getSubject().PHP_EOL; fwrite($this->myfile, $txt);
        $mailQueue->setPreheader( langTextController::getLangText( $this->utils, $this->getLocale(), 'MAIL_WELCOME_PREHEADER' ) );
//$txt = 'Preheader '.$mailQueue->getPreHeader().PHP_EOL; fwrite($this->myfile, $txt);

        $link = $this->startup->getUrlApp().'/activate-user/' . $this->getActivationKey();
//$txt = 'Activation link '.$link.PHP_EOL; fwrite($this->myfile, $txt);
        $mailQueue->addAssignVar( 'activation_link', $link );

        $link = $this->startup->getUrlApp(). '/'.langTextController::getLangText( $this->utils, $this->getLocale(), 'SECURITY_LOGIN_LINK' );
//$txt = 'Login link '.$link.PHP_EOL; fwrite($this->myfile, $txt);
        $mailQueue->addAssignVar('login_link', $link );

        $mailQueue->addAssignVar('user_email', $this->getEmail() );

        $mailQueue->persist();
//$txt = 'Mail ============ '.$mailQueue->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($mailQueue->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     *
     * Sends user activation email
     *
     * @return void
     */
    public function send_activation_email()
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'User ================== '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']));

        $minutes_to_add = 1;

        $mailQueue = new mailQueueController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $send = $now->add(new DateInterval('PT' . $minutes_to_add . 'M'));
        $mailQueue->setSend( $send );

        $mailQueue->setToName( $this->getName() );
        $mailQueue->setLocale( $this->getLocale() );
        $mailQueue->setToAddress( $this->getEmail() );

        $mailQueue->setTemplate('user_activate');
        $mailQueue->setProcess(__METHOD__ );

        $subject = langTextController::getLangText( $this->utils, $this->getLocale(), 'MAIL_ACTIVATION_ACCOUNT_SUBJECT');
        $mailQueue->setSubject($this->session->config['web_name'].' - '.$subject );
//$txt = 'Subject '.$mailQueue->getSubject().PHP_EOL; fwrite($this->myfile, $txt);
        $preheader = langTextController::getLangText( $this->utils, $this->getLocale(), 'MAIL_ACTIVATION_NEEDED_PREHEADER');
        $mailQueue->setPreheader($this->session->config['web_name'].' - '.$preheader );
//$txt = 'Preheader '.$mailQueue->getPreHeader().PHP_EOL; fwrite($this->myfile, $txt);

        $link = $this->startup->getUrlApp().'/activate-user/' . $this->getActivationKey();
//$txt = 'Activation link '.$link.PHP_EOL; fwrite($this->myfile, $txt);
        $mailQueue->addAssignVar( 'activation_link', $link );

        $mailQueue->persist();
//$txt = 'Mail ============ '.$mailQueue->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($mailQueue->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }
}
