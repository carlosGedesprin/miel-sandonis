<?php

namespace src\controller\entity;

use src\controller\baseController;

class regionNameController extends baseController
{
    use repository\regionNameRepositoryController;

    private $table = 'region_name';
    private $reg = array(
                            'id'              => '',
                            'region_code'     => NULL,
                            'lang_2a'         => NULL,
                            'name'            => NULL,
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
     * Get region name from his id
     *
     * Not make sense
     *
     */
    public function getRegbyId( $id )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Id ==========> ('.$id.')'.PHP_EOL; fwrite($this->myfile, $txt);
        if ( !$id ) return false;

        $route = '/get_region_name_by_id';
        $api_data = array(
                            'id' => $id,
        );

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $this->getRegFromDB( $route, $api_data );
    }

    /**
     *
     * Get region name from his region code and lang_2a
     *
     */
    public function getRegbyRegionCodeAndLang2a( $region_code, $lang_2a )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Region code ==========> ('.$region_code.')'.' Lang code_2a ==========> ('.$lang_2a.')'.PHP_EOL; fwrite($this->myfile, $txt);
        if ( !$region_code || !$lang_2a ) return false;

        $route = '/get_region_name_by_region_code';
        $api_data = array(
                            'region_code' => $region_code,
                            'lang_2a' => $lang_2a,
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
$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
$txt = 'Route ==========> ('.$route.')'.PHP_EOL; fwrite($this->myfile, $txt);
$txt = 'Api data ==========> '.PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r($api_data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $api_data['api_key'] = $this->session->config['locations_api_key'];

        if ( $item = $this->utils->get_from_locations_api( $route, $api_data ) )
        {
$txt = 'From Api ==========> '.PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r($item, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

            $this->reg = array_merge( $this->reg, $item['msg'] );
//$txt = 'Reg merged ==========> '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->reg, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

            $this->loadSpecialFields();

$txt = 'reg found==========> ('.$this->getId().')'.PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r($this->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
            return true;
        }
        else
        {
$txt = 'reg NOT found ==========> '.PHP_EOL; fwrite($this->myfile, $txt);
$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
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

        $route = '/set_region_name';
        $api_data = array(
                            'api_key' => $this->session->config['locations_api_key'],
                            'region_name' => $this->getReg(),
        );
        $result = $this->utils->get_from_locations_api( $route, $api_data );
//$txt = 'Result ==> '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($result, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $this->setId( $result['msg']['id'] );

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

        $route = '/delete_region_name';
        $api_data = array(
                            'api_key' => $this->session->config['locations_api_key'],
                            'region_name' => $this->getReg(),
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
    public function setRegionCode( $region_code ) { $this->reg['region_code'] = $region_code;  }
    public function setLang2a( $lang_2a ) { $this->reg['lang_2a'] = $lang_2a;  }
    public function setName( $name ) { $this->reg['name'] = $name;  }

    public function getReg() { return $this->reg; }
    public function getAll( $filter='', $extra='' ) { return $this->db->fetchAll( $this->table, '*', $filter, $extra ); }
    public function getId() { return $this->reg['id']; }
    public function getRegionCode() { return $this->reg['region_code']; }
    public function getLang2a() { return $this->reg['lang_2a']; }
    public function getName() { return $this->reg['name']; }

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
