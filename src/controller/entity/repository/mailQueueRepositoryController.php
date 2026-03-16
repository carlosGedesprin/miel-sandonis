<?php

namespace src\controller\entity\repository;

use \src\controller\entity\langTextController;
use \src\controller\entity\langTextNameController;

/**
 * Trait mailQueue
 * @package entity
 */
trait mailQueueRepositoryController
{
    private function setLanguageforSpecialVars()
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Locale '.$this->getLocale().PHP_EOL; fwrite($this->myfile, $txt);
        $lang_text = new langTextController(array('env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang));
        $lang_text_name = new langTextNameController(array('env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang));

        //$text_base_mail_contact_us_link = $lang_text_name->getRegbyLangTextAndLang( 'WEB_MENU_CONTACT_LINK', $this->getLocale() );
        $lang_text->getRegbyLangKey( 'WEB_MENU_CONTACT_LINK', );
        $lang_text_name->getRegbyLangTextAndLang( $lang_text->getId(), $this->getLocale() );
        $text_base_mail_contact_us_link = $lang_text_name->getText();

        //$text_base_mail_contact_us = $lang_text_name->getRegbyLangTextAndLang( 'WEB_MENU_CONTACT', $this->getLocale() );
        $lang_text->getRegbyLangKey( 'WEB_MENU_CONTACT', );
        $lang_text_name->getRegbyLangTextAndLang( $lang_text->getId(), $this->getLocale() );
        $text_base_mail_contact_us = $lang_text_name->getText();

        //$text_base_mail_legal_stuff_link = $lang_text_name->getRegbyLangTextAndLang( 'WEB_FOOTER_LEGALSTUFF_LINK', $this->getLocale() );
        $lang_text->getRegbyLangKey( 'WEB_FOOTER_LEGALSTUFF_LINK', );
        $lang_text_name->getRegbyLangTextAndLang( $lang_text->getId(), $this->getLocale() );
        $text_base_mail_legal_stuff_link = $lang_text_name->getText();

        //$text_base_mail_legal_stuff = $lang_text_name->getRegbyLangTextAndLang( 'WEB_FOOTER_LEGALSTUFF', $this->getLocale() );
        $lang_text->getRegbyLangKey( 'WEB_FOOTER_LEGALSTUFF', );
        $lang_text_name->getRegbyLangTextAndLang( $lang_text->getId(), $this->getLocale() );
        $text_base_mail_legal_stuff = $lang_text_name->getText();

        //$text_base_mail_cookies_policy_link = $lang_text_name->getRegbyLangTextAndLang( 'WEB_FOOTER_COOKIES_POLICY_LINK', $this->getLocale() );
        $lang_text->getRegbyLangKey( 'WEB_FOOTER_COOKIES_POLICY_LINK', );
        $lang_text_name->getRegbyLangTextAndLang( $lang_text->getId(), $this->getLocale() );
        $text_base_mail_cookies_policy_link = $lang_text_name->getText();

        //$text_base_mail_cookies_policy = $lang_text_name->getRegbyLangTextAndLang( 'WEB_FOOTER_COOKIES_POLICY', $this->getLocale() );
        $lang_text->getRegbyLangKey( 'WEB_FOOTER_COOKIES_POLICY', );
        $lang_text_name->getRegbyLangTextAndLang( $lang_text->getId(), $this->getLocale() );
        $text_base_mail_cookies_policy = $lang_text_name->getText();

        //$text_base_mail_privacy_policy_link = $lang_text_name->getRegbyLangTextAndLang( 'WEB_FOOTER_PRIVACY_POLICY_LINK', $this->getLocale() );
        $lang_text->getRegbyLangKey( 'WEB_FOOTER_PRIVACY_POLICY_LINK', );
        $lang_text_name->getRegbyLangTextAndLang( $lang_text->getId(), $this->getLocale() );
        $text_base_mail_privacy_policy_link = $lang_text_name->getText();

        //$text_base_mail_privacy_policy = $lang_text_name->getRegbyLangTextAndLang( 'WEB_FOOTER_PRIVACY_POLICY', $this->getLocale() );
        $lang_text->getRegbyLangKey( 'WEB_FOOTER_PRIVACY_POLICY', );
        $lang_text_name->getRegbyLangTextAndLang( $lang_text->getId(), $this->getLocale() );
        $text_base_mail_privacy_policy = $lang_text_name->getText();

        //$text_base_mail_termsandconditions_link = $lang_text_name->getRegbyLangTextAndLang( 'WEB_FOOTER_TERMSANDCONDITIONS_LINK', $this->getLocale() );
        $lang_text->getRegbyLangKey( 'WEB_FOOTER_TERMSANDCONDITIONS_LINK', );
        $lang_text_name->getRegbyLangTextAndLang( $lang_text->getId(), $this->getLocale() );
        $text_base_mail_termsandconditions_link = $lang_text_name->getText();

        //$text_base_mail_termsandconditions = $lang_text_name->getRegbyLangTextAndLang( 'WEB_FOOTER_TERMSANDCONDITIONS', $this->getLocale() );
        $lang_text->getRegbyLangKey( 'WEB_FOOTER_TERMSANDCONDITIONS', );
        $lang_text_name->getRegbyLangTextAndLang( $lang_text->getId(), $this->getLocale() );
        $text_base_mail_termsandconditions = $lang_text_name->getText();

        //$text_base_mail_unsubscribe_link = $lang_text_name->getRegbyLangTextAndLang( 'BASE_MAIL_UNSUBSCRIBE_LINK', $this->getLocale() );
        $lang_text->getRegbyLangKey( 'BASE_MAIL_UNSUBSCRIBE_LINK', );
        $lang_text_name->getRegbyLangTextAndLang( $lang_text->getId(), $this->getLocale() );
        $text_base_mail_unsubscribe_link = $lang_text_name->getText();

        //$text_base_mail_unsubscribe = $lang_text_name->getRegbyLangTextAndLang( 'BASE_MAIL_UNSUBSCRIBE', $this->getLocale() );
        $lang_text->getRegbyLangKey( 'BASE_MAIL_UNSUBSCRIBE', );
        $lang_text_name->getRegbyLangTextAndLang( $lang_text->getId(), $this->getLocale() );
        $text_base_mail_unsubscribe = $lang_text_name->getText();

        //$text_base_mail_eco_footer = $lang_text_name->getRegbyLangTextAndLang( 'BASE_MAIL_ECO_FOOTER', $this->getLocale() );
        $lang_text->getRegbyLangKey( 'BASE_MAIL_ECO_FOOTER', );
        $lang_text_name->getRegbyLangTextAndLang( $lang_text->getId(), $this->getLocale() );
        $text_base_mail_eco_footer = $lang_text_name->getText();

        //$text_base_mail_t_and_c = $lang_text_name->getRegbyLangTextAndLang( 'BASE_MAIL_T_AND_C', $this->getLocale() );
        $lang_text->getRegbyLangKey( 'BASE_MAIL_T_AND_C', );
        $lang_text_name->getRegbyLangTextAndLang( $lang_text->getId(), $this->getLocale() );
        $text_base_mail_t_and_c = $lang_text_name->getText();

        //$text_base_mail_team = $lang_text_name->getRegbyLangTextAndLang( 'BASE_MAIL_TEAM', $this->getLocale() );
        $lang_text->getRegbyLangKey( 'BASE_MAIL_TEAM', );
        $lang_text_name->getRegbyLangTextAndLang( $lang_text->getId(), $this->getLocale() );
        $text_base_mail_team = $lang_text_name->getText();
//$txt = 'Team ('.$text_base_mail_team.')'.PHP_EOL; fwrite($this->myfile, $txt);

        $this->addAssignVar( 'site_link', $this->startup->getUrlApp() );
        $this->addAssignVar( 'language' , $this->getLocale() );
        //$this->addAssignVar( 'title'  , $this->session->config['email_system_name'].' - '.$text_title_activation_account );
        $this->addAssignVar( 'contact_us_link', $text_base_mail_contact_us_link );
        $this->addAssignVar( 'contact_us', $text_base_mail_contact_us );
        $this->addAssignVar( 'legal_stuff_link', $text_base_mail_legal_stuff_link );
        $this->addAssignVar( 'legal_stuff', $text_base_mail_legal_stuff );
        $this->addAssignVar( 'cookies_policy_link', $text_base_mail_cookies_policy_link );
        $this->addAssignVar( 'cookies_policy', $text_base_mail_cookies_policy );
        $this->addAssignVar( 'privacy_policy_link', $text_base_mail_privacy_policy_link );
        $this->addAssignVar( 'privacy_policy', $text_base_mail_privacy_policy );
        $this->addAssignVar( 'termsandconditions_link', $text_base_mail_termsandconditions_link );
        $this->addAssignVar( 'termsandconditions', $text_base_mail_termsandconditions );
        $this->addAssignVar( 'unsubscribe_link', $text_base_mail_unsubscribe_link );
        $this->addAssignVar( 'unsubscribe', $text_base_mail_unsubscribe );
        $this->addAssignVar( 'copyright'  , $this->session->config['web_copy_right'] );
        $this->addAssignVar( 'eco_footer' , sprintf( $text_base_mail_eco_footer, $this->session->config['web_name'] ) );
        $this->addAssignVar( 't_and_c' , sprintf( $text_base_mail_t_and_c, $this->session->config['web_name'], $this->session->config['web_info_email'] ) );
//$txt = 'Web name '.$this->session->config['web_name'].PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Team with web name =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->getAssignVars(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        $this->addAssignVar( 'team' , sprintf( $text_base_mail_team, $this->session->config['web_name']) );
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }
}
