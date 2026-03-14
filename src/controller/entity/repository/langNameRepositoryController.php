<?php

namespace src\controller\entity\repository;


/**
 * Trait langName
 * @package entity
 */
trait langNameRepositoryController
{
    /**
     *
     * Delete all names from a lang by code_2a
     *
     */
    public function deleteByLangCode2a( $lang_code_2a )
    {
//$txt = '====================== '.__FUNCTION__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Lang code 2a ==========> ('.$lang_code_2a.')'.PHP_EOL; fwrite($this->myfile, $txt);

        $filter_select = ['lang_code_2a' => $lang_code_2a];
        $extra_select = '';
        $langs = $this->getAll( $filter_select, $extra_select );
        foreach ( $langs as $key => $lang_temp )
        {
            $this->getRegbyId( $lang_temp['id'] );
            $this->delete();
        }
    }
}
