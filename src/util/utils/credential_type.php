<?php
namespace src\util\utils;

/**
 * Trait credential type
 * @package Utils
 */
trait credential_type
{
    /**
     * Get the credential tyep name. Used in Twig filters.
     *
     * @param int $id       credential_type id
     * @return mixed    credential_type name
     */
    public function getCredentialTypeName( $id )
    {
        return $this->db->fetchField('credential_type', 'name', ['id' => $id]);
    }
}
