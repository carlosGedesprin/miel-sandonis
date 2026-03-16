<?php

namespace src\controller\views\control_panel;

use \src\controller\baseViewController;

use \src\controller\entity\accountController;
use \src\controller\entity\userController;

class dashboardViewController extends baseViewController
{
    private $folder = 'control_panel';

    /**
     * @Route("app/dashboard", name="app_dashboard")
     */
    public function dashboardAction()
    {
        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/dashboard.html.twig');
    }
}
