<?php
namespace src\util\utils;

use src\controller\entity\sectorController;

/**
 * Trait website
 * @package Utils
 */
trait website
{
    /**
     */
    public function getSectors()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/utils_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $sector = new sectorController( array( 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'db' => $this->db ) );

        $filter_select = ['active' => '1'];
        $extra_select = 'ORDER BY `name`';
        $sectors = $sector->getAll($filter_select, $extra_select);

//$txt = 'Sectors =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($sectors, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose( $this->myfile );
        return $sectors;
    }
}
