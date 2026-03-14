<?php

namespace src\controller\entity\verifications;

use DateTime;
use DateTimeZone;

/**
 * Trait solution verifications
 * @package entity
 */
trait solutionVerificationController
{
    /*
     *
     * Check if another solution has the same name
     *
     * $name : name to search
     * $solution_id : id to be avoided
     *
     */
    public function solutionsWithSameName( $name, $solution_id=NULL )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Name '.$name.' solution_id '.$solution_id.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
        $avoid_self_record = ( $solution_id != NULL && $solution_id != '0')? ' AND `id` <> \''.$solution_id.'\'' : '';

        $rows = $this->getAll( ['name' => $name], $avoid_self_record );
//$txt = 'Found ==> '.sizeof($rows).PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return ( sizeof( $rows ) > 0 )? true : false;
    }
    /*
     *
     * Check if another solution has the same slug
     *
     * $slug : slug to search
     * $solution_id : id to be avoided
     *
     */
    public function solutionsWithSameSlug( $slug, $solution_id=NULL )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Slug '.$slug.' solution_id '.$solution_id.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
        $avoid_self_record = ( $solution_id != NULL && $solution_id != '0')? ' AND `id` <> \''.$solution_id.'\'' : '';

        $rows = $this->getAll( ['slug' => $slug], $avoid_self_record );
//$txt = 'Found ==> '.sizeof($rows).PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return ( sizeof( $rows ) > 0 )? true : false;
    }
}
