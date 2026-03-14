<?php

namespace src\controller\entity\verifications;

use DateTime;
use DateTimeZone;

/**
 * Trait supplier verifications
 * @package entity
 */
trait supplierVerificationController
{
    /*
     *
     * Check if another supplier has the same name
     *
     * $name : name to search
     * $supplier_id : id to be avoided
     *
     */
    public function suppliersWithSameName( $name, $supplier_id=NULL )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Name '.$name.' supplier_id '.$supplier_id.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
        $avoid_self_record = ( $supplier_id != NULL && $supplier_id != '0')? ' AND `id` <> \''.$supplier_id.'\'' : '';

        $rows = $this->getAll( ['name' => $name], $avoid_self_record );
//$txt = 'Found ==> '.sizeof($rows).PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return ( sizeof( $rows ) > 0 )? true : false;
    }
    /*
     *
     * Check if another supplier has the same slug
     *
     * $slug : slug to search
     * $supplier_id : id to be avoided
     *
     */
    public function suppliersWithSameSlug( $slug, $supplier_id=NULL )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Slug '.$slug.' supplier_id '.$supplier_id.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
        $avoid_self_record = ( $supplier_id != NULL && $supplier_id != '0')? ' AND `id` <> \''.$supplier_id.'\'' : '';

        $rows = $this->getAll( ['slug' => $slug], $avoid_self_record );
//$txt = 'Found ==> '.sizeof($rows).PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return ( sizeof( $rows ) > 0 )? true : false;
    }
}
