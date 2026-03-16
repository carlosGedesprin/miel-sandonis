<?php
namespace src\util\utils;

use src\controller\entity\categoryController;

/**
 * Trait category
 * @package Utils
 */
trait category
{
    /**
     * Get the category name
     */
    public function getCategoryName( $id )
    {
        $category = new categoryController( array( 'env' => $_ENV, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => array(), 'db' => $this->db, 'utils' => array(), 'session' => array(), 'lang' => array() ) );

        $category->getRegbyId( $id );

        return $category->getName();
    }
}
