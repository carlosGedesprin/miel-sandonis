<?php
namespace src\util\utils;

/**
 * Trait entity_contact
 * @package Utils
 */
trait entity_contact
{
    /**
    * Get the entity contact name
    *
    * @param $id      entity contact id
    * @return mixed   Entity contact name
    */
    public function getEntityContactName( $id )
    {
        return $this->db->fetchField('entity_contact', 'name', ['id' => $id]);
    }
}
