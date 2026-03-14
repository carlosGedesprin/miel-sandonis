<?php

namespace src\controller\web;

use \src\controller\baseViewController;

use DateTime;
use DateTimeZone;

class solutionController extends baseViewController
{
    /**
     * @Route("/web/solution/instagram-grow", name="solution_instagram_grow")
     */
    public function solutionInstagramGrowAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/solutionController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose( $this->myfile );
        return $this->twig->render('web/'.$this->session->config['website_skin'].'/solution_instagram_grow.html.twig', array(
            'data' => $this->data,
        ));
    }

    /**
     * @Route("/web/solution/invoicing", name="solution_invoicing")
     */
    public function solutionInvoicingAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/solutionController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose( $this->myfile );
        return $this->twig->render('web/'.$this->session->config['website_skin'].'/solution_invoicing.html.twig', array(
            'data' => $this->data,
        ));
    }

    /**
     * @Route("/web/solution/personal-assistant", name="solution_personal_assistant")
     */
    public function solutionPersonalAssistantAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/solutionController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose( $this->myfile );
        return $this->twig->render('web/'.$this->session->config['website_skin'].'/solution_personal_assistant.html.twig', array(
            'data' => $this->data,
        ));
    }

    /**
     * @Route("/web/solution/bookings-agent", name="solution_bookings_agent")
     */
    public function solutionBookingsAgentAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/solutionController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose( $this->myfile );
        return $this->twig->render('web/'.$this->session->config['website_skin'].'/solution_bookings_agent.html.twig', array(
            'data' => $this->data,
        ));
    }

    /**
     * @Route("/web/solution/agent-no-show", name="solution_agent_no_show")
     */
    public function solutionAgentNoShowAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/solutionController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose( $this->myfile );
        return $this->twig->render('web/'.$this->session->config['website_skin'].'/solution_agent_no_show.html.twig', array(
            'data' => $this->data,
        ));
    }

    /**
     * @Route("/web/solution/linkedin-instagram-scrapping", name="solution_linkedin_instagram_scrapping")
     */
    public function solutionLinkedinInstagramScrappingAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/solutionController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose( $this->myfile );
        return $this->twig->render('web/'.$this->session->config['website_skin'].'/solution_linkedin_instagram_scrapping.html.twig', array(
            'data' => $this->data,
        ));
    }

    /**
     * @Route("/web/solution/web-scraping", name="solution_web_scraping")
     */
    public function solutionWebScrapingAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/solutionController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose( $this->myfile );
        return $this->twig->render('web/'.$this->session->config['website_skin'].'/solution_web_scraping.html.twig', array(
            'data' => $this->data,
        ));
    }
}