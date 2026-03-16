<?php

namespace src\controller\views\control_panel;

use \src\controller\baseViewController;

use \src\controller\entity\accountController;
use \src\controller\entity\userController;

use \src\controller\web\securityViewController;

class loadInfoController extends baseViewController
{
    private $loadInfo;

    public function __construct( $args )
    {
$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/loadInfoController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
$txt = 'loadInfoController '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        parent::__construct( $args );
        if ( empty ( $this->loadInfo ) )
        {
            $this->setLoadInfo();
        }
$txt = 'loadInfoController '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     *
     * Load account ID, email and customer domain
     *
     */
    private function loadInfoAction()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/loadInfoController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '======================'.__METHOD__.' start ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//$txt = 'Post:'.PHP_EOL; fwrite( $this->myfile, $txt );
//fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Session Selected Widget: '.PHP_EOL; fwrite( $this->myfile, $txt );
//fwrite($this->myfile, print_r($_SESSION['selected_web'], TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $user = new userController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang, 'twig' => $this->twig ) );

//$txt = 'User on Session: '.$this->user.PHP_EOL; fwrite( $this->myfile, $txt );
        if ( $this->user = '' || !$user->getRegbyId( $this->user ) )
        {
            $security = new securityViewController(array('env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang));
            echo $security->expiredAction();
            exit;
        }

        $account = new accountController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $account->getRegbyId( $user->getAccount() );

        $dashboard_data['account_id'] = $account->getId();
        $dashboard_data['account_key'] = substr( $account->getAccountKey(),-7 ) ;
        $dashboard_data['account_name'] = $account->getName();
        $dashboard_data['account_main_user'] = $account->getMainUser();
        $dashboard_data['email'] = $user->getEmail();

        $this->loadInfo = $dashboard_data;
//$txt = '======================'.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
    }

    public function getLoadInfo()
    {
        return $this->loadInfo;
    }

    private function setLoadInfo()
    {
        $this->loadInfoAction();
    }
}
