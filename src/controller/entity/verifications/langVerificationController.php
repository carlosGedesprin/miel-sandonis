<?php

namespace src\controller\entity\verifications;

use DateTime;
use DateTimeZone;

/**
 * Trait Lang text verifications
 * @package entity
 */
trait langVerificationController
{
    /*
     *
     * Check if another lang text has the same lang iso name
     *
     * $lang_iso_name : lang iso name to search
     * $key : '', 'id' or 'lang_iso_name' key to avoid the self record
     * $lang_id : id or lang_iso_name referred on the $key
     *
     */
    public function langTextWithSameLangKey( $lang_iso_name, $key, $key_value=NULL )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Lang iso name '.$lang_iso_name.' key '.$key.' key_value '.$key_value.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
        $filter_select = array('lang_iso_name' => $lang_iso_name);
//$txt = 'Filters ==> '.print_r($filters, true).PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
        switch ( $key ) {
            case '':
                $avoid_self_record = '';
                break;
            case 'id':
                $avoid_self_record = ( $key_value != NULL && $key_value != '0')? ' AND `id` <> \''.$key_value.'\'' : '';
                break;
            case 'lang_iso_name':
                $avoid_self_record = ( $key_value != NULL && $key_value != '0')? ' AND `lang_iso_name` <> \''.$key_value.'\'' : '';
                break;
        }
        $extra_select = $avoid_self_record;

        $rows = $this->getAll( $filter_select, $extra_select );
//$txt = 'Found ==> '.sizeof($rows).PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return ( sizeof( $rows ) > 0 )? true : false;
    }
}
