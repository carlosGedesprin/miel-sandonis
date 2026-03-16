<?php

namespace src\controller\entity\repository;

use DateTime;
use DateTimeZone;

/**
 * Trait sub sector
 * @package entity
 */
trait subSectorRepositoryController
{
    /**
     *
     * Create a sub sector
     */
    public function createSubSector ( $sector, $name )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $this->setId( '' );
        $this->setSector( $sector );
        $this->setName( $name );
        $this->persist();

        $this->setKey( md5( $this->getId() ) );
        $this->persist();
//$txt = 'Sub sector ========>' . PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->getReg(), TRUE));
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }
}
