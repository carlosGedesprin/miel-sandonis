<?php

namespace src\controller\entity;

use src\controller\baseController;

class regionController extends baseController
{
    use repository\regionRepositoryController;

    private $table = 'region';
    private $reg = array(
                            'id'              => '',
                            'country_code_2a' => NULL,
                            'region_code'     => NULL,
                            'iso_code'        => NULL,
                            'flag'            => NULL,
                        );

    /**
     *
     * Get table name
     *
     */
    public function getTableName()
    {
        return $this->table;
    }

    /**
     *
     * Get region from his id
     *
     */
    public function getRegbyId( $id )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Id ==========> ('.$id.')'.PHP_EOL; fwrite($this->myfile, $txt);
        if ( !$id ) return false;

        $route = '/get_region_by_id';
        $api_data = array(
                            'id' => $id,
        );

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $this->getRegFromDB( $route, $api_data );
    }

    /**
     *
     * Get region from region_code
     *
     */
    public function getRegbyRegionCode( $region_code )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Region code ==========> ('.$region_code.')'.PHP_EOL; fwrite($this->myfile, $txt);
        if ( !$region_code ) return false;

        $route = '/get_region_by_region_code';
        $api_data = array(
                            'region_code' => $region_code,
        );

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $this->getRegFromDB( $route, $api_data );
    }

    /**
     *
     * Get reg
     *
     */
    private function getRegFromDB( $route, $api_data )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Route ==========> ('.$route.')'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Api data ==========> '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($api_data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $api_data['api_key'] = $this->session->config['locations_api_key'];

        if ( $item = $this->utils->get_from_locations_api( $route, $api_data ) )
        {
//$txt = 'From Api ==========> '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($item, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

            $this->reg = array_merge( $this->reg, $item['msg'] );
//$txt = 'Reg merged ==========> '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->reg, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

            $this->loadSpecialFields();

//$txt = 'reg found==========> ('.$this->getId().')'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
            return true;
        }
        else
        {
//$txt = 'reg NOT found ==========> '.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
            return false;
        }
    }

    /**
     *
     * Persist to db
     *
     */
    public function persist()
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Table ==> ('.$this->table.') User ==> ('.$this->user.')'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->reg, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $this->setSpecialFields();

        $route = '/set_region';
        $api_data = array(
                            'api_key' => $this->session->config['locations_api_key'],
                            'region' => $this->getReg(),
        );
        $result = $this->utils->get_from_locations_api( $route, $api_data );
//$txt = 'Result ==> '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($result, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $this->setId( $result['msg']['id'] );
        $this->setRegionCode( $result['msg']['region_code'] );

        $this->loadSpecialFields();

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $this->getId();
    }

    /**
     *
     * Delete this record
     *
     */
    public function delete()
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Id ==========> ('.$this->getId().')'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->reg, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $route = '/delete_region';
        $api_data = array(
                            'api_key' => $this->session->config['locations_api_key'],
                            'region' => $this->getReg(),
        );
        $result = $this->utils->get_from_locations_api( $route, $api_data );
//$txt = 'Result ==> '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($result, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        if ( $result['status'] == 'OK' )
        {
//$txt = 'reg deleted ==========> '.$this->reg['id'].PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
            return true;
        }
        else
        {
//$txt = 'reg NOT deleted ==========> '.$this->reg['id'].PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
            return false;
        }
    }

    public function setReg( $reg ) { $this->reg = array_merge( $this->reg, $reg );  }
    public function setId( $id ) { $this->reg['id'] = $id;  }
    public function setCountryCode2a( $country_code_2a ) { $this->reg['country_code_2a'] = $country_code_2a;  }
    public function setRegionCode( $region_code ) { $this->reg['region_code'] = $region_code;  }
    public function setIsoCode( $iso_code ) { $this->reg['iso_code'] = $iso_code;  }
    public function setFlag( $flag ) { $this->reg['flag'] = $flag;  }

    public function getReg() { return $this->reg; }
    public function getAll( $filter='', $extra='' ) { return $this->db->fetchAll( $this->table, '*', $filter, $extra ); }
    public function getId() { return $this->reg['id']; }
    public function getCountryCode2a() { return $this->reg['country_code_2a']; }
    public function getRegionCode() { return $this->reg['region_code']; }
    public function getIsoCode() { return $this->reg['iso_code']; }
    public function getFlag() { return $this->reg['flag']; }

    /**
     *
     * Change special fields after load from database
     */
    private function loadSpecialFields()
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     *
     * Change special fields before save to database
     */
    private function setSpecialFields()
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }
}
