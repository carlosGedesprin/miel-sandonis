<?php

namespace src\controller\entity\verifications;

use DateTime;
use DateTimeZone;

/**
 * Trait rag verifications
 * @package entity
 */
trait ragVerificationController
{

    /*
     *
     * Check if another rag has the same name
     *
     * $name : name to search
     * $type_key : '', 'id' or 'key' key to avoid the self record
     * $rag_id : id or rag_key referred on the $type_key
     * $account: Name not duplicated per account, expected account id
     */
    public function ragsWithSameName( $name, $type_key, $key, $account )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Name ('.$name.') Type key ('.$type_key.') Key ('.$key.') Account ('.$account.')'.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
        $filters = array(
                        'name' => $name,
                        'account' => $account
        );
//$txt = 'Filters ==> '.print_r($filters, true).PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
        $extra = ( $key == 0 )? '' : ' AND '.( ( $type_key == 'id' )? 'id' : 'rag_key').' <> \''.$key.'\'';
//$txt = 'Extra ==> '.$extra.PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);

        $rows = $this->getAll( $filters, $extra );
//$txt = 'Found ==> '.sizeof($rows).PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return ( sizeof( $rows ) > 0 )? true : false;
    }
}
