<?php
namespace src\util\utils;

/**
 * Trait group
 * @package Utils
 */
trait group
{
    /**
     * Get the group name. Used in Twig filters.
     *
     * @param int $id       Group id
     * @return mixed    Group name
     */
    public function getGroupName( $id )
    {
        return $this->db->fetchField('group', 'name', ['id' => $id]);
    }
    /**
     * Get the group Capital Letter. Used in Twig filters.
     *
     * @param int $id       Group id
     * @return mixed    Group capital letter
     */
    public function getGroupCapitalLetter( $id )
    {
        $groupCapitalLetter = 'Undefined';
        switch ( $id )
        {
            case GROUP_SUPER_ADMIN:
                $groupCapitalLetter = 'SA';
                break;
            case GROUP_ADMIN:
                $groupCapitalLetter = 'A';
                break;
            case GROUP_STAFF:
                $groupCapitalLetter = 'Staff';
                break;
            case GROUP_CUSTOMER:
                $groupCapitalLetter = 'Cli';
                break;
            case GROUP_AGENT:
                $groupCapitalLetter = 'Age';
                break;
        }
        return $groupCapitalLetter;
    }

    /**
     * Get the group folder.
     *
     * @param int $id       Group id
     * @return mixed    Group folder
     */
    public function getGroupFolder( $id )
    {
        return $this->db->fetchField('group', 'folder', ['id' => $id]);
    }
}
