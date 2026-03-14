<?php

namespace src\controller\entity\verifications;

use DateTime;
use DateTimeZone;

/**
 * Trait user verifications
 * @package entity
 */
trait userVerificationController
{
    /*
     *
     * Check if another user has the same email
     *
     * @param $email string Email to search
     * @param $key string '', 'id' or 'user_key' key to avoid the self record
     * @param $user_id string id or user_key referred on the $key
     *
     */
    public function usersWithSameEmail( $email, $key, $user_id=NULL )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Email ==> ('.$email.') Key ==> '.$key.' User_id ==> '.$user_id.PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
        switch ($key) {
            case '':
                $rows = $this->db->fetchAll( $this->table, 'id', ['email' => $email] );
                break;
            case 'id':
                $rows = $this->db->fetchAll( $this->table, 'id', ['email' => $email], ' AND id <> '.$user_id );
                break;
            case 'user_key':
                $rows = $this->db->fetchAll( $this->table, 'id', ['email' => $email], ' AND user_key <> "'.$user_id.'"' );
                break;
        }
//$txt = 'Found ==> '.( sizeof( $rows ) > 0 )? 'Yes' : 'No'.PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        return ( sizeof( $rows ) > 0 )? true : false;
    }

    /**
     *
     * Validations
     *
     * Return how many users belongs to an account
     */
    public function howManyUsersOnAccount( $account )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $users = $this->db->fetchAll( $this->table, 'id', ['account' => $account ]);

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return sizeof( $users );
    }
}
