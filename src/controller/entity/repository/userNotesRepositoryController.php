<?php

namespace src\controller\entity\repository;


/**
 * Trait userNotes
 * @package entity
 */
trait userNotesRepositoryController
{
    /**
     *
     * Get all notes from user id
     */
    public function getAllRegsByUser( $id )
    {
        return $this->getAll( ['user' => $id] );
    }
}
