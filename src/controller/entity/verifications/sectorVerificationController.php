<?php

namespace src\controller\entity\verifications;

use DateTime;
use DateTimeZone;

/**
 * Trait sector verifications
 * @package entity
 */
trait sectorVerificationController
{
    /*
     *
     * Check if another sector has the same name
     *
     * $name : name to search
     * $sector_id : id to be avoided
     *
     */
    public function sectorsWithSameName( $name, $sector_id=NULL )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Name '.$name.' Sector_id '.$sector_id.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
        $avoid_self_record = ( $sector_id != NULL && $sector_id != '0')? ' AND `id` <> \''.$sector_id.'\'' : '';

        $rows = $this->getAll( ['name' => $name], $avoid_self_record );
//$txt = 'Found ==> '.sizeof($rows).PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return ( sizeof( $rows ) > 0 )? true : false;
    }
    /*
     *
     * Check if another sector has the same slug
     *
     * $slug : slug to search
     * $sector_id : id to be avoided
     *
     */
    public function sectorsWithSameSlug( $slug, $sector_id=NULL )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Slug '.$slug.' Sector_id '.$sector_id.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
        $avoid_self_record = ( $sector_id != NULL && $sector_id != '0')? ' AND `id` <> \''.$sector_id.'\'' : '';

        $rows = $this->getAll( ['slug' => $slug], $avoid_self_record );
//$txt = 'Found ==> '.sizeof($rows).PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return ( sizeof( $rows ) > 0 )? true : false;
    }
}
