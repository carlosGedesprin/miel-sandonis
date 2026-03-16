<?php
namespace src\util\utils;

use src\controller\entity\ragController;
/**
 * Trait rag
 * @package Utils
 */
trait rag
{
    /**
     * Get the rag name
     *
     * @param $id      rag id
     * @return mixed   RAG name
     */
    public function getRagName( $id )
    {
        return $this->db->fetchField('rag', 'name', ['id' => $id]);
    }
}