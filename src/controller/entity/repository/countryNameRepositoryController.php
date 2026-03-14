<?php

namespace src\controller\entity\repository;


/**
 * Trait countryName
 * @package entity
 */
trait countryNameRepositoryController
{
    /**
     *
     * Delete all names from a country by country_code_2a
     *
     */
    public function deleteByCountryCode2a( $country_code_2a )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Country code 2a ==========> ('.$country_code_2a.')'.PHP_EOL; fwrite($this->myfile, $txt);

        $route = '/delete_country_names';
        $api_data = array(
                            'api_key' => $this->session->config['locations_api_key'],
                            'country_code_2a' => $country_code_2a,
        );
        $result = $this->utils->get_from_locations_api( $route, $api_data );

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $result;
    }

    /**
     *
     * Check if country names can be populated to db
     *
     */
    public function checkEdit( $action )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Reg sent for check ==========> '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $route = '/country_name/edit/check';
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
     * Check if country names can be deleted from db
     *
     */
    public function checkDelete()
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Reg sent for check ==========> '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $route = '/country_name/delete/check';
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
