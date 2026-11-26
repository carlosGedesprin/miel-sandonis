<?php

namespace src\controller\web;

use \src\controller\baseViewController;


use DateTime;
use DateTimeZone;

class webController extends baseViewController
{
    private $data;

    public function __construct( $args )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/webController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Args =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($args, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        parent::__construct( $args );

        // Las páginas públicas ya no usan idiomas alternativos, canonical generado aquí ni "sectores"
        // (restos de la web anterior). La URL canónica la pone la plantilla general a partir de this_route.
        $this->data = array();
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/webController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

//$txt = 'Data =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose( $this->myfile );
        return $this->twig->render('web/'.$this->session->config['website_skin'].'/index.html.twig', array(
            'data' => $this->data,
        ));
    }
    /**
     * @Route("/about-us", name="/about-us")
     */
    public function aboutUsAction()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/webController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

//$txt = 'Data =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
        return $this->twig->render('web/'.$this->session->config['website_skin'].'/about_us.html.twig', array(
            'data' => $this->data,
        ));
    }
    /**
     * @Route("/conservation", name="conservation")
     */
    public function conservationAction()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/webController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

//$txt = 'Data =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
        return $this->twig->render('web/'.$this->session->config['website_skin'].'/conservation.html.twig', array(
            'data' => $this->data,
        ));
    }
    /**
     * @Route("/our_honney", name="our_honney")
     */
    public function ourHonneyAction()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/webController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

//$txt = 'Data =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
        return $this->twig->render('web/'.$this->session->config['website_skin'].'/our_honney.html.twig', array(
            'data' => $this->data,
        ));
    }

    /**
     * Página de contacto: solo muestra los datos de contacto (el formulario se eliminó).
     * @Route("/contact", name="contact")
     */
    public function contactAction()
    {
        return $this->twig->render('web/'.$this->session->config['website_skin'].'/contactus.html.twig', array(
            'data' => $this->data,
        ));
    }
}
