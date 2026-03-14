<?php

namespace src\controller\entity\verifications;

use DateTime;
use DateTimeZone;

/**
 * Trait category verifications
 * @package entity
 */
trait categoryVerificationController
{
    /*
     *
     * Check if another category has the same name
     *
     * $name : name to search
     * $category_id : id to be avoided
     *
     */
    public function categoriesWithSameName( $name, $category_id=NULL )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Name '.$name.' Category_id '.$category_id.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
        $avoid_self_record = ( $category_id != NULL && $category_id != '0')? ' AND `id` <> \''.$category_id.'\'' : '';

        $rows = $this->getAll( ['name' => $name], $avoid_self_record );
//$txt = 'Found ==> '.sizeof($rows).PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return ( sizeof( $rows ) > 0 )? true : false;
    }
    /*
     *
     * Check if another category has the same slug
     *
     * $slug : slug to search
     * $category_id : id to be avoided
     *
     */
    public function categoriesWithSameSlug( $slug, $category_id=NULL )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Slug '.$slug.' Category_id '.$category_id.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
        $avoid_self_record = ( $category_id != NULL && $category_id != '0')? ' AND `id` <> \''.$category_id.'\'' : '';

        $rows = $this->getAll( ['slug' => $slug], $avoid_self_record );
//$txt = 'Found ==> '.sizeof($rows).PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return ( sizeof( $rows ) > 0 )? true : false;
    }
}
