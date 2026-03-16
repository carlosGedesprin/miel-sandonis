<?php
namespace src\util\utils;

/**
 * Trait entity
 * @package Utils
 */
trait entity
{
    /**
    * Get the entity name
    *
    * @param $id      entity id
    * @return mixed   Entity name
    */
    public function getEntityName( $id )
    {
        return $this->db->fetchField('entity', 'name', ['id' => $id]);
    }
}
