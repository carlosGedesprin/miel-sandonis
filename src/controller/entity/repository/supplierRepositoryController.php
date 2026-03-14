<?php

namespace src\controller\entity\repository;

use DateTime;
use DateTimeZone;

/**
 * Trait supplier
 * @package entity
 */
trait supplierRepositoryController
{
    /**
     *
     * Create an supplier
     */
    public function createSupplier( $name )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $this->setName( $name );
        $this->persist();

        $this->setSupplierKey( md5( $this->getId() ) );
        $this->persist();
//$txt = 'Supplier ========>' . PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->getReg(), TRUE));
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }
}
