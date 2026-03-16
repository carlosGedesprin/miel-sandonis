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
     * @Route("/", name="templates")
     */
    public function templatesAction()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/webController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

//$txt = 'Data =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose( $this->myfile );
        return $this->twig->render('web/'.$this->session->config['website_skin'].'/templates.html.twig', array(
            'data' => $this->data,
        ));
    }
    /**
     * @Route("/solutions", name="solutions")
     */
    public function solutionsAction()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/webController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

//$txt = 'Data =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
        return $this->twig->render('web/'.$this->session->config['website_skin'].'/solutions.html.twig', array(
            'data' => $this->data,
        ));
    }
    /**
     * @Route("/solution_invoices", name="solution_invoices")
     */
    public function solutionInvoicesAction()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/webController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $this->data['hero_h1'] = $this->lang['WEB_SOLUTION_INVOICES_H1'];
        $this->data['hero_h2'] = $this->lang['WEB_SOLUTION_INVOICES_H2'];
        $this->data['hero_p'] = $this->lang['WEB_SOLUTION_INVOICES_P'];
//$txt = 'Data =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
        return $this->twig->render('web/'.$this->session->config['website_skin'].'/solution_invoices.html.twig', array(
            'data' => $this->data,
        ));
    }
    /**
     * @Route("/solution_expense_notes", name="solution_expense_notes")
     */
    public function solutionExpenseNotesAction()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/webController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $this->data['hero_h1'] = 'ExpenseNotes - Soluciones prácticas para trabajar mejor'; //$this->lang['WEB_SOLUTION_INVOICES_H1'];
        $this->data['hero_h2'] = 'ExpenseNotes - Tecnología que se adapta a tu ritmo y al de tu equipo'; //$this->lang['WEB_SOLUTION_INVOICES_H2'];
        $this->data['hero_p'] =  'ExpenseNotes - Te mostramos cómo funciona esta herramienta para agilizar tus tareas diarias y ganar tiempo desde el primer día.'; //$this->lang['WEB_SOLUTION_INVOICES_P'];
//$txt = 'Data =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
        return $this->twig->render('web/'.$this->session->config['website_skin'].'/solution_expense_notes.html.twig', array(
            'data' => $this->data,
        ));
    }
    /**
     * @Route("/solution_personal_assistant", name="solution_personal_assistant")
     */
    public function solutionPersonalAssistantAction()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/webController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $this->data['hero_h1'] = 'PersonalAssistant - Soluciones prácticas para trabajar mejor'; //$this->lang['WEB_SOLUTION_INVOICES_H1'];
        $this->data['hero_h2'] = 'PersonalAssistant - Tecnología que se adapta a tu ritmo y al de tu equipo'; //$this->lang['WEB_SOLUTION_INVOICES_H2'];
        $this->data['hero_p'] =  'PersonalAssistant - Te mostramos cómo funciona esta herramienta para agilizar tus tareas diarias y ganar tiempo desde el primer día.'; //$this->lang['WEB_SOLUTION_INVOICES_P'];
//$txt = 'Data =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
        return $this->twig->render('web/'.$this->session->config['website_skin'].'/solution_personal_assistant.html.twig', array(
            'data' => $this->data,
        ));
    }
    /**
     * @Route("/sectors", name="sectors")
     */
    public function sectorsAction()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/webController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

//$txt = 'Data =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
        return $this->twig->render('web/'.$this->session->config['website_skin'].'/sectors.html.twig', array(
            'data' => $this->data,
        ));
    }
    /**
     * @Route("/sectors", name="sectors")
     *
     * $r->addRoute(['GET', 'POST'], '/sector/{sector_name}', 'web/webController:sectorAction');
     */
    public function sectorAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/webController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $sector = new sectorController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        if ( $sector->getRegbySlug( $vars['sector_slug'] ) )
        {
            $twig_file_name = 'sector';
            $this->data['sector_slug'] = $vars['sector_slug'];
        }
        else
        {
            $twig_file_name = 'sectors';
        }
//$txt = 'Data =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
        return $this->twig->render('web/'.$this->session->config['website_skin'].'/'.$twig_file_name.'.html.twig', array(
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
     * @Route("/unsubscribe", name="unsubscribe")
     */
    public function unsubscribeAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/webController_'.__FUNCTION__.'.txt', 'a+') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);

        $data = array(
                        'email'   => $vars['email'],
/*
                        'name'    => $this->utils->request_var( 'name', '', 'ALL'),
                        'surname' => $this->utils->request_var( 'surname', '', 'ALL'),
                        'message' => $this->utils->request_var( 'message', '', 'ALL'),
                        'submit' => (isset($_POST['btn_submit'])) ? true : false,
*/
        );
//$txt = 'email ===>'.$data['email'].PHP_EOL;fwrite($this->myfile, $txt);

//$txt = PHP_EOL.'vars =============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($vars, TRUE));
//$txt = PHP_EOL.'Data =============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($data, TRUE));

        $lead = new leadController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        if ( $lead->getRegbyEmail( $data['email']) )
        {
//$txt = 'Lead found ('.$lead->getId().')'.PHP_EOL; fwrite($this->myfile, $txt);
            $lead->setSend( '0' );
            $lead->setBlocked( '1' );
            $lead->persist();
        }
        else
        {
//$txt = 'Lead NOT found '.PHP_EOL; fwrite($this->myfile, $txt);
        }
        return $this->twig->render('web/'.$this->session->config['website_skin'].'/unsubscribe.html.twig', array(
            'data' => $data,
        ));
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
//fclose($this->myfile);
    }
}