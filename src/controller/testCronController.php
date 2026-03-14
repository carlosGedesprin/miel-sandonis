<?php

namespace src\controller;

use DateTime;
use DateTimeZone;
use Exception;

class testCronController extends baseViewController
{
    /**
     * Test a cron process
     *
     * @param $vars array process - Process to run
     */
    public function cronTestAction( $vars )
    {
$this->myfile = fopen(APP_ROOT_PATH . '/var/logs/testCronController_' . __FUNCTION__ . '.txt', 'w') or die('Unable to open file!');
$txt = '====================== ' . __METHOD__ . ' start ===============================================================' . PHP_EOL; fwrite($this->myfile, $txt);

        $file_name = APP_ROOT_PATH . '/src/controller/cron/'.$vars['process'].'Functions.php';

$txt = 'Process to run ('.$vars['process'].') file name ('.$file_name.')'.PHP_EOL; fwrite($this->myfile, $txt);
        if ( file_exists( $file_name) )
        {
$txt = 'File exists ('.$file_name.')'.PHP_EOL; fwrite($this->myfile, $txt);
            require_once $file_name;
            $class_to_load = '\\src\\controller\\cron\\' . $vars['process'] . 'Functions';
$txt = 'Class to load ('.$class_to_load.')'.PHP_EOL; fwrite($this->myfile, $txt);
            //$_ENV, $this->logger, $this->startup, $this->db, $this->utils, $this->session, $this->lang, $this->twig
            $processor = new $class_to_load($_ENV, $this->logger, $this->logger_err, $this->startup, $this->db, $this->utils, $this->session, $this->lang, $this->twig);
$txt = 'Done '.PHP_EOL; fwrite($this->myfile, $txt);
        }
        else
        {
$txt = 'File not exists ('.$file_name.')'.PHP_EOL; fwrite($this->myfile, $txt);
        }
$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }
}