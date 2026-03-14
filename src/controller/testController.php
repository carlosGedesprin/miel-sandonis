<?php

namespace src\controller;

use src\controller\entity\userController;

use DateTime;
use DateTimeZone;
use Exception;

class testController extends baseController
{
    /**
     * Test utils function usersWithSameEmail
     *
     */
    public function testUsersWithSameEmail( $vars )
    {
$this->myfile = fopen(APP_ROOT_PATH . '/var/logs/testController_' . __FUNCTION__ . '.txt', 'w') or die('Unable to open file!');
$txt = '====================== ' . __METHOD__ . ' start ===============================================================' . PHP_EOL; fwrite($this->myfile, $txt);

        $user = new userController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        if ( $user->usersWithSameEmail( $vars['user_notifications_email'], '' ) )
        {
$txt = 'Email '.$vars['user_notifications_email'].' exists on users table'.PHP_EOL; fwrite($this->myfile, $txt);
        }
        else
        {
$txt = 'Email '.$vars['user_notifications_email'].' NOT exists on users table'.PHP_EOL; fwrite($this->myfile, $txt);
        }

$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }
}