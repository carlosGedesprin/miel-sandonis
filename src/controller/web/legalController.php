<?php

namespace src\controller\web;

use \src\controller\baseViewController;
use \src\controller\entity\leadController;

use DateTime;
use DateTimeZone;

class legalController extends baseViewController
{
    private $data;

    /**
     * @Route("/legalstuff", name="legalstuff")
     */
    public function legalstuffAction()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/webController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
// $txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
        return $this->twig->render('web/'.$this->session->config['website_skin'].'/legalstuff.html.twig', array(
            'data' => $this->data,
        ));
    }

    /**
     * @Route("/termsandconditions", name="termsandconditions")
     */
    public function termsandconditionsAction()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/webController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
// $txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
        return $this->twig->render('web/'.$this->session->config['website_skin'].'/terms_and_conditions.html.twig', array(
            'data' => $this->data,
        ));
    }

    /**
     * @Route("/privacypolicy", name="privacypolicy")
     */
    public function privacypolicyAction()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/webController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
// $txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
        return $this->twig->render('web/'.$this->session->config['website_skin'].'/privacy_policy.html.twig', array(
            'data' => $this->data,
        ));
    }

    /**
     * @Route("/cookiespolicyAction", name="cookiespolicyAction")
     */
    public function cookiespolicyAction()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/webController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
// $txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
        return $this->twig->render('web/'.$this->session->config['website_skin'].'/cookiespolicy.html.twig', array(
            'data' => $this->data,
        ));
    }

    /**
     * @Route("/paymentterms", name="paymentterms")
     */
    public function paymenttermsAction()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/webController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
// $txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
        return $this->twig->render('/web/'.$this->session->config['website_skin'].'/payment_terms.html.twig', array(
            'data' => $this->data,
        ));
    }

    /**
     * @Route("/accessibility_statement", name="accessibility_statement")
     */
    public function accessibilityStatementAction()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/webController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
// $txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);

        $this->data['entity'] = $this->session->config['web_name'];
        $this->data['domain_name'] = $this->session->config['web_domain'];
        $this->data['revision_date'] = '2025-08-10';
        $this->data['contact_link'] = $this->lang['WEB_MENU_CONTACT_LINK'];
        $this->data['opens_new_window_icon'] = '/assets/images/web/'.$this->session->config['website_skin'].'/external-link-alt-solid.png';

        return $this->twig->render('/web/'.$this->session->config['website_skin'].'/accessibility_statement.html.twig', array(
            'data' => $this->data,
        ));
    }
}