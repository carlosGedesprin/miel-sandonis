<?php

namespace src\controller\entity\verifications;

use DateTime;
use DateTimeZone;

/**
 * Trait server verifications
 * @package entity
 */
trait serverVerificationController
{
    /*
     *
     * Check if another server has the same name
     *
     * $name : name to search
     * $type_key : '', 'id' or 'key' key to avoid the self record
     * $server_id : id or server_key referred on the $type_key
     * $account: Name not duplicated per account, expected account id
     */
    public function serversWithSameName( $name, $type_key, $key, $account )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Name ('.$name.') Type key ('.$type_key.') Key ('.$key.') Account ('.$account.')'.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
        $filters = array(
                        'name' => $name,
                        'account' => $account
        );
//$txt = 'Filters ==> '.print_r($filters, true).PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
        $extra = ( $key == 0 )? '' : ' AND '.( ( $type_key == 'id' )? 'id' : 'server_key').' <> \''.$key.'\'';
//$txt = 'Extra ==> '.$extra.PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);

        $rows = $this->getAll( $filters, $extra );
//$txt = 'Found ==> '.sizeof($rows).PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return ( sizeof( $rows ) > 0 )? true : false;
    }
    /*
     *
     * Check if another server has the same server name
     *
     * $name : name to search
     * $type_key : '', 'id' or 'key' key to avoid the self record
     * $server_id : id or server_key referred on the $type_key
     * $account: Name not duplicated per account, expected account id
     */
    public function serversWithSameServerName( $name, $type_key, $key, $account )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Name ('.$name.') Type key ('.$type_key.') Key ('.$key.') Account ('.$account.')'.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
        $filters = array(
                        'server_name' => $name,
                        'account' => $account
        );
//$txt = 'Filters ==> '.print_r($filters, true).PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
        $extra = ( $key == 0 )? '' : ' AND '.( ( $type_key == 'id' )? 'id' : 'server_key').' <> \''.$key.'\'';
//$txt = 'Extra ==> '.$extra.PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);

        $rows = $this->getAll( $filters, $extra );
//$txt = 'Found ==> '.sizeof($rows).PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return ( sizeof( $rows ) > 0 )? true : false;
    }
}
