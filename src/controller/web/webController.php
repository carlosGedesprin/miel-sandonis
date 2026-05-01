<?php

namespace src\controller\web;

use \src\controller\baseViewController;

use \src\controller\entity\langController;
use \src\controller\entity\langNameController;
use \src\controller\entity\sectorController;

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

        $lang = new langController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $lang_name = new langNameController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $this->data['canonical'] = '<link rel="canonical" href="'.$_ENV['protocol'].'://'.$_ENV['domain'].'%this_route%" />';

        $this->data['alternate_langs'] = '';
        $this->data['langs'] = '';
        $filter_select = array(
                                'active' => '1',
        );
        $extra_select = 'ORDER BY `code_2a`';
        $langs = $lang->getAll( $filter_select, $extra_select);
        foreach( $langs as $lang_temp_key => $lang_temp_value )
        {
            $lang->getRegbyId( $lang_temp_value['id'] );

            $this->data['alternate_langs'] .= '<link rel="alternate" hreflang="'.$lang->getCode2a().'" href="'.$_ENV['protocol'].'://'.$_ENV['domain'].'%this_route%?lang='.$lang->getCode2a().'" />';

            if ( $lang->getCode2a() != $this->session->getLanguageCode2a() )
            {
                $lang_name->getRegbyCodeAndLang( $lang->getCode2a(), $this->session->getLanguageCode2a());
                $this->data['langs'] .= '<a href="'.$_ENV['protocol'].'://'.$_ENV['domain'].'%this_route%?lang='.$lang->getCode2a().'"
                                            class="header_contact_lang">
                                             <img src="/assets/images/web/'.$this->session->config['website_skin'].'/lang_flags/'.$lang->getCode2a().'.png" title="'.$lang_name->getName().'" />
                                         </a>';
            }
        }

        $this->data['sectors'] = $this->utils->getSectors();
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
     * @Route("/blog", name="blog")
     */
    public function blogAction()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/webController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

//$txt = 'Data =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
        return $this->twig->render('web/'.$this->session->config['website_skin'].'/blog.html.twig', array(
            'data' => $this->data,
        ));
    }
}