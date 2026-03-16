<?php

namespace src\controller\entity\repository;

use \src\controller\entity\blogCategoryController;

use src\controller\entity\langTextController;
use src\controller\entity\langTextNameController;

/**
 * Trait blog article lang
 * @package entity
 */
trait blogArticleLangRepositoryController
{
    /**
     *
     * Delete Langs from a article
     *
     * @return void
     */
    public function deleteLangs( $article_id )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $filter_select = array(
                                'article' => $article_id,
        );
        $extra_select = '';
        $rows = $this->getAll( $filter_select, $extra_select);
        foreach ( $rows as $row )
        {
            $this->getRegbyId( $row['id'] );
            $this->deleteORL();
        }
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }
}
