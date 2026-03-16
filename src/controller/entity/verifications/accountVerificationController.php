<?php

namespace src\controller\entity\verifications;

use DateTime;
use DateTimeZone;

/**
 * Trait account verifications
 * @package entity
 */
trait accountVerificationController
{
    /**
     *
     * Return if a user is the main user in account
     */
    public function isUserMainInAccount( $user, $account )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'user ('.$user->getId().')'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'account ('.$account->getId().')'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Main user ('.$account->getMainUser().')'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = ( $user->getId() == $account->getMainUser() )? 'is main user'.PHP_EOL : 'NOT is main user'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return ( $user->getId() == $account->getMainUser() )? true : false;
    }

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
     * Check if another account has the same notifications email
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
                $rows = $this->db->fetchAll( $this->table, 'id', ['notifications_email' => $email] );
                break;
            case 'id':
                $rows = $this->db->fetchAll( $this->table, 'id', ['notifications_email' => $email], ' AND `id` <> \''.$account_id.'\'' );
                break;
            case 'account_key':
                $rows = $this->db->fetchAll( $this->table, 'id', ['notifications_email' => $email], ' AND `account_key` <> \''.$account_id.'\'' );
                break;
        }
//$txt = 'Found ==> '.sizeof($rows).PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return ( sizeof( $rows ) > 0 )? true : false;
    }
}
