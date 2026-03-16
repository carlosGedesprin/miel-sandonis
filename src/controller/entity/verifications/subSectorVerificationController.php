<?php

namespace src\controller\entity\verifications;

use DateTime;
use DateTimeZone;

/**
 * Trait sub sector verifications
 * @package entity
 */
trait subSectorVerificationController
{
    /*
     *
     * Check if another sub sector has the same name
     *
     * $name : name to search
     * $sub_sector_id : id to be avoided
     *
     */
    public function subSectorsWithSameName( $name, $sub_sector_id=NULL )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Name '.$name.' Sub sector_id '.$sub_sector_id.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
        $avoid_self_record = ( $sub_sector_id != NULL && $sub_sector_id != '0')? ' AND `id` <> \''.$sub_sector_id.'\'' : '';

        $rows = $this->getAll( ['name' => $name], $avoid_self_record );
//$txt = 'Found ==> '.sizeof($rows).PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return ( sizeof( $rows ) > 0 )? true : false;
    }
    /*
     *
     * Check if another sub sector has the same slug
     *
     * $slug : slug to search
     * $sub_sector_id : id to be avoided
     *
     */
    public function subSectorsWithSameSlug( $slug, $sub_sector_id=NULL )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Slug '.$slug.' Sub sector_id '.$sub_sector_id.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
        $avoid_self_record = ( $sub_sector_id != NULL && $sub_sector_id != '0')? ' AND `id` <> \''.$sub_sector_id.'\'' : '';

        $rows = $this->getAll( ['slug' => $slug], $avoid_self_record );
//$txt = 'Found ==> '.sizeof($rows).PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return ( sizeof( $rows ) > 0 )? true : false;
    }
}
