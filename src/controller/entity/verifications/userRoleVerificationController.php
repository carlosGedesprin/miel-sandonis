<?php

namespace src\controller\entity\verifications;

use DateTime;
use DateTimeZone;

/**
 * Trait user role verifications
 * @package entity
 */
trait userRoleVerificationController
{
    /*
     *
     * Check if another user role has the same name lang key
     *
     * $name_lang_key : name to search
     * $key : '', 'id' key to avoid the self record
     *
     */
    public function userRoleWithSameName( $name_lang_key, $key )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Name_lang_key '.$name_lang_key.' Key '.$key.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
        $filters = array('name_lang_key' => $name_lang_key);
//$txt = 'Filters ==> '.print_r($filters, true).PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
        if ( empty( $key ) )
        {
            $avoid_self_record = '';
        }
        else
        {
            $avoid_self_record = ' AND `id` <> \''.$key.'\'';
        }

        $rows = $this->db->fetchAll( $this->table, 'id', $filters, $avoid_self_record );
//$txt = 'Found ==> '.sizeof($rows).PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return ( sizeof( $rows ) > 0 )? true : false;
    }
    /*
     *
     * Check if another user role has the same role
     *
     * $rol : rol to search
     * $key : '', 'id' key to avoid the self record
     *
     */
    public function userRoleWithSameRole( $role, $key )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Role '.$role.' Key '.$key.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
        $filters = array('role' => $role);
//$txt = 'Filters ==> '.print_r($filters, true).PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
        if ( empty( $key ) )
        {
            $avoid_self_record = '';
        }
        else
        {
            $avoid_self_record = ' AND `id` <> \''.$key.'\'';
        }

        $rows = $this->db->fetchAll( $this->table, 'id', $filters, $avoid_self_record );
//$txt = 'Found ==> '.sizeof($rows).PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return ( sizeof( $rows ) > 0 )? true : false;
    }
}
