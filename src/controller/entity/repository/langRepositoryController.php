<?php

namespace src\controller\entity\repository;


/**
 * Trait lang
 * @package entity
 */
trait langRepositoryController
{
    /**
    *
    * Get all only active langs
    *
    */
    public function getActiveLangs()
    {
$txt = '====================== '.__FUNCTION__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $filter_select = '';
        $extra_select = ' WHERE ACTIVE = "1"';
        $result = $this->getAll( $filter_select, $extra_select );
$txt = 'Langs =========='.PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r($result, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
$txt = '====================== '.__FUNCTION__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $result;
    }
    /**
    *
    * Get all active and pre-active langs
    *
    */
    public function getActiveAndPreActive()
    {
//$txt = '====================== '.__FUNCTION__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Id ==========> ('.$id.')'.PHP_EOL; fwrite($this->myfile, $txt);

        $filter_select = '';
        $extra_select = 'WHERE ACTIVE IN ("1", "2")';
        $result = $this->getAll( $filter_select, $extra_select );
//$txt = 'Langs =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($result, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__FUNCTION__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $result;
    }
    /**
     *
     * Delete all lang names for a lang
     *
     */
    public function deleteByLang( $lang_id )
    {
//$txt = '====================== '.__FUNCTION__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Lang id ==========> ('.$lang_id.')'.PHP_EOL; fwrite($this->myfile, $txt);

        $filter_select = ['lang' => $lang_id];
        $extra_select = '';
        $lang_names = $this->getAll( $filter_select, $extra_select );
//$txt = 'Lang text names =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($result, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        foreach ( $lang_names as $lang_name_key => $lang_name_value )
        {
            $this->getRegbyId( $lang_name_value['id'] );
            $this->delete();
        }
//$txt = '====================== '.__FUNCTION__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }
}
