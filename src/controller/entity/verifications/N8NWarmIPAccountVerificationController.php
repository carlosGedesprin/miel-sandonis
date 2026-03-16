<?php

namespace src\controller\entity\verifications;

use DateTime;
use DateTimeZone;

/**
 * Trait warm IP account verifications
 * @package entity
 */
trait N8NWarmIPAccountVerificationController
{
    /*
     *
     * Check if another account has the same name
     *
     * $name : name to search
     * $key : '', 'id' or 'account_key' key to avoid the self record
     * $account_id : id or account_key referred on the $key
     *
     */
    public function accountsWithSameName( $name, $key, $account_id=NULL )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = $name.' '.$key.' '.$account_id.' '.$type.' '.$type_id.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
        $filters = array('name' => $name);
//$txt = 'Filters ==> '.print_r($filters, true).PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
        switch ( $key ) {
            case '':
                $avoid_self_record = '';
                break;
            case 'id':
                $avoid_self_record = ( $account_id != NULL && $account_id != '0')? ' AND `id` <> \''.$account_id.'\'' : '';
                break;
            case 'account_key':
                $avoid_self_record = ( $account_id != NULL && $account_id != '0')? ' AND `account_key` <> \''.$account_id.'\'' : '';
                break;
        }

        $rows = $this->db->fetchAll( $this->table, 'id', $filters, $avoid_self_record );
//$txt = 'Found ==> '.sizeof($rows).PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return ( sizeof( $rows ) > 0 )? true : false;
    }

    /*
     * Validation
     *
     * Check if another account has the same email
     *
     * $email : email to search
     * $key : '', 'id' or 'account_key' key to avoid the self record
     * $account_id : id or account_key referred on the $key
     *
     */
    public function accountsWithSameEmail( $email, $key, $account_id=NULL )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Email ==> ('.$email.') Key ==> '.$key.' Account_id ==> '.$account_id.PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
        switch ($key) {
            case '':
                $rows = $this->db->fetchAll( $this->table, 'id', ['email' => $email] );
                break;
            case 'id':
                $rows = $this->db->fetchAll( $this->table, 'id', ['email' => $email], ' AND `id` <> \''.$account_id.'\'' );
                break;
            case 'account_key':
                $rows = $this->db->fetchAll( $this->table, 'id', ['email' => $email], ' AND `account_key` <> \''.$account_id.'\'' );
                break;
        }
//$txt = 'Found ==> '.sizeof($rows).PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return ( sizeof( $rows ) > 0 )? true : false;
    }
}
