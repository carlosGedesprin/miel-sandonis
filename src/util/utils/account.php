<?php
namespace src\util\utils;

/**
 * Trait account
 * @package Utils
 */
trait account
{
    /**
    * Get the account name
    *
    * @param $id      account id
    * @return mixed   Account name
    */
    public function getAccountName( $id )
    {
        return $this->db->fetchField('account', 'name', ['id' => $id]);
    }

    /**
    * Get the account key
    *
    * @param $id      account id
    * @return mixed   Account key
    */
    public function getAccountKey( $id )
    {
        return $this->db->fetchField('account', 'account_key', ['id' => $id]);
    }

    /**
    * Get the account main user
    *
    * @param $id      account id
    * @return integer   Account main user id
    */
    public function getAccountMainUser( $id )
    {
        return $this->db->fetchField('account', 'main_user', ['id' => $id]);
    }

    /**
     * Get the account record
     *
     * @param $id       account id
     * @return string   Account record
     */
    public function getAccountDetails( $id )
    {
        $row = $this->db->fetchOne('account', '*', ['id' => $id]);
        return $row;
    }
}
