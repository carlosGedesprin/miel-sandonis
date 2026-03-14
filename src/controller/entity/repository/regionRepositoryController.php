<?php

namespace src\controller\entity\repository;


/**
 * Trait region
 * @package entity
 */
trait regionRepositoryController
{
    /**
     *
     * Get all regions for lists and selects
     *
     */
    public function getActiveAndPreActive()
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $route = '/get_regions';
        $api_data = array(
                            'api_key' => $this->session->config['locations_api_key'],
                            'lang' => $this->session->getLanguageCode2a(),

        );
        $result = $this->utils->get_from_locations_api( $route, $api_data );
//$txt = 'Regions from api =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($result, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $result;
    }

    /**
     *
     * Check if region can be populated to db
     *
     */
    public function checkEdit( $action )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Reg sent for check ==========> '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $route = '/region/edit/check';
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
     * Check if region can be deleted from db
     *
     */
    public function checkDelete()
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Reg sent for check ==========> '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $route = '/region/delete/check';
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
