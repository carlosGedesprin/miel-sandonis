<?php

namespace src\controller\entity\repository;

use \src\controller\entity\langTextController;

/**
 * Trait langName
 * @package entity
 */
trait langTextNameRepositoryController
{
    /**
     *
     * Delete all lang text names for a lang text
     *
     */
    public function deleteByLangText( $lang_text_id )
    {
//$txt = '====================== '.__FUNCTION__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Lang text id ==========> ('.$lang_text_id.')'.PHP_EOL; fwrite($this->myfile, $txt);

        $filter_select = ['lang_text' => $lang_text_id];
        $extra_select = '';
        $lang_text_names = $this->getAll( $filter_select, $extra_select );
//$txt = 'Lang text names =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($result, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        foreach ( $lang_text_names as $lang_text_name_key => $lang_text_name_value )
        {
            $this->getRegbyId( $lang_text_name_value['id'] );
            $this->delete();
        }
//$txt = '====================== '.__FUNCTION__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }
}
