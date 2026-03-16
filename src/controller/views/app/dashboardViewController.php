<?php

namespace src\controller\views\app;

use \src\controller\baseViewController;


class dashboardViewController extends baseViewController
{
    private $folder = 'app';

    /**
     * @Route("app/dashboard", name="app_dashboard")
     */
    public function dashboardAction()
    {
        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/dashboard.html.twig');
    }
}
