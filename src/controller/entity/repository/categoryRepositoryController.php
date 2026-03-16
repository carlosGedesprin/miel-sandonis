<?php

namespace src\controller\entity\repository;

use DateTime;
use DateTimeZone;

/**
 * Trait category
 * @package entity
 */
trait categoryRepositoryController
{
    /**
     *
     * Create a category
     */
    public function createCategory ( $name )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $this->setName( $name );
        $this->persist();

        $this->setCategoryKey( md5( $this->getId() ) );
        $this->persist();
//$txt = 'Account ========>' . PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->getReg(), TRUE));
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }
}
