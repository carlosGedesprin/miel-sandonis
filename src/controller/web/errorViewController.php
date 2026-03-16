<?php

namespace src\controller\web;

use \src\controller\baseViewController;

class errorViewController extends baseViewController
{
    /**
     * @Route('/400error', name='400error')
     */
    public function error400Action()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/errorController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = 'errorController '.__FUNCTION__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);

        return $this->twig->render('web/'.$this->session->config['website_skin'].'/errorPages/error400.html.twig');
    }

    /**
     * @Route('/405error', name='405error')
     */
    public function error405Action()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/errorController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = 'errorController '.__FUNCTION__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
        return $this->twig->render('web/'.$this->session->config['website_skin'].'/errorPages/error405.html.twig');
    }

    /**
     * @Route('/404error', name='404error')
     */
    public function error404Action()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/errorController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = 'errorController '.__FUNCTION__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
        return $this->twig->render('web/'.$this->session->config['website_skin'].'/errorPages/error404.html.twig');
    }

    /**
     * @Route('/500error', name='500error')
     */
    public function error500Action()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/errorController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = 'errorController '.__FUNCTION__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
        return $this->twig->render('web/'.$this->session->config['website_skin'].'/errorPages/error500.html.twig');
    }
}
