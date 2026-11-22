<?php

namespace src\controller\web;

use \src\controller\baseViewController;

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
}