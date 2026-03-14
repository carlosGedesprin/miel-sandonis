<?php

namespace src\controller\entity\repository;

use DateTime;
use DateTimeZone;

/**
 * Trait solution
 * @package entity
 */
trait solutionRepositoryController
{
    /**
     *
     * Create a solution
     */
    public function createSolution( $name )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $this->setId( '' );
        $this->setName( $name );
        $this->persist();

        $this->setKey( md5( $this->getId() ) );
        $this->persist();
//$txt = 'Solution ========>' . PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->getReg(), TRUE));
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }
}
