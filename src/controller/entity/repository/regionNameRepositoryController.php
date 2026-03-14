<?php

namespace src\controller\entity\repository;


/**
 * Trait regionName
 * @package entity
 */
trait regionNameRepositoryController
{
    /**
     *
     * Delete all names from a region by region_code
     *
     */
    public function deleteByRegionCode( $region_code )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Region code ==========> ('.$region_code.')'.PHP_EOL; fwrite($this->myfile, $txt);

        $route = '/delete_region_names';
        $api_data = array(
                            'api_key' => $this->session->config['locations_api_key'],
                            'region_code' => $region_code,
        );
        $result = $this->utils->get_from_locations_api( $route, $api_data );

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $result;
    }

    /**
     *
     * Check if region names can be populated to db
     *
     */
    public function checkEdit( $action )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Reg sent for check ==========> '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $route = '/region_name/edit/check';
        $api_data = array(
                            'api_key' => $this->session->config['locations_api_key'],
                            'action' => $action,
                            'reg' => $this->getReg(),
        );
        $result = $this->utils->get_from_locations_api( $route, $api_data );
//$txt = 'Checks from api =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($result, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $result;
    }

    /**
     *
     * Check if region names can be deleted from db
     *
     */
    public function checkDelete()
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Reg sent for check ==========> '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $route = '/region_name/delete/check';
        $api_data = array(
                            'api_key' => $this->session->config['locations_api_key'],
                            'reg' => $this->getReg(),
        );
        $result = $this->utils->get_from_locations_api( $route, $api_data );
//$txt = 'Checks from api =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($result, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $result;
    }
}
