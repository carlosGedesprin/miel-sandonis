<?php

namespace src\controller\entity\verifications;

use DateTime;
use DateTimeZone;

/**
 * Trait Lang text verifications
 * @package entity
 */
trait langTextVerificationController
{
    /*
     *
     * Check if another lang text has the same lang key
     *
     * $lang_key : lang key to search
     * $key : '', 'id' or 'lang_key' key to avoid the self record
     * $lang_text_id : id or lang_key referred on the $key
     *
     */
    public function langTextWithSameLangKey( $lang_key, $key, $key_value=NULL )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Lang key '.$lang_key.' '.key.' '.$key.' key_value '.$key_value.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
        $filter_select = array('lang_key' => $lang_key);
//$txt = 'Filters ==> '.print_r($filters, true).PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
        switch ( $key ) {
            case '':
                $avoid_self_record = '';
                break;
            case 'id':
                $avoid_self_record = ( $key_value != NULL && $key_value != '0')? ' AND `id` <> \''.$key_value.'\'' : '';
                break;
            case 'lang_key':
                $avoid_self_record = ( $key_value != NULL && $key_value != '')? ' AND `lang_key` <> \''.$key_value.'\'' : '';
                break;
        }
        $extra_select = $avoid_self_record;

        $rows = $this->getAll( $filter_select, $extra_select );
//$txt = 'Found ==> '.sizeof($rows).PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return ( sizeof( $rows ) > 0 )? true : false;
    }
}
