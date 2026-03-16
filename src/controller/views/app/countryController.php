<?php

namespace src\controller\entity;

use src\controller\baseController;

class countryController extends baseController
{
    use repository\countryRepositoryController;

    private $table = 'country';
    private $reg = array(
                            'id'         => '',
                            'code_2a'    => NULL,
                            'code_3a'    => NULL,
                            'code_3n'    => NULL,
                            'flag'       => NULL,
                            'phone_code' => NULL,
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
     * Get country from his id
     *
     */
    public function getRegbyId( $id )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Id ==========> ('.$id.')'.PHP_EOL; fwrite($this->myfile, $txt);
        if ( !$id ) return false;

        $route = '/get_country_by_id';
        $api_data = array(
                            'id' => $id,
        );

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $this->getRegFromDB( $route, $api_data );
    }

    /**
     *
     * Get country from code_2a
     *
     */
    public function getRegbyCode2a( $code_2a )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Code_2a ==========> ('.$code_2a.')'.PHP_EOL; fwrite($this->myfile, $txt);
        if ( !$code_2a ) return false;

        $route = '/get_country_by_code_2a';
        $api_data = array(
                            'code_2a' => $code_2a,
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

        $route = '/set_country';
        $api_data = array(
                            'api_key' => $this->session->config['locations_api_key'],
                            'country' => $this->getReg(),
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

        $route = '/delete_country';
        $api_data = array(
                            'api_key' => $this->session->config['locations_api_key'],
                            'country' => $this->getReg(),
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
    public function setCode2a( $code_2a ) { $this->reg['code_2a'] = $code_2a;  }
    public function setCode3a( $code_3a ) { $this->reg['code_3a'] = $code_3a;  }
    public function setCode3n( $code_3n ) { $this->reg['code_3n'] = $code_3n;  }
    public function setFlag( $flag ) { $this->reg['flag'] = $flag;  }
    public function setPhoneCode( $phone_code ) { $this->reg['phone_code'] = $phone_code;  }

    public function getReg() { return $this->reg; }
    public function getAll( $filter='', $extra='' ) { return $this->db->fetchAll( $this->table, '*', $filter, $extra ); }
    public function getId() { return $this->reg['id']; }
    public function getCode2a() { return $this->reg['code_2a']; }
    public function getCode3a() { return $this->reg['code_3a']; }
    public function getCode3n() { return $this->reg['code_3n']; }
    public function getFlag() { return $this->reg['flag']; }
    public function getPhoneCode() { return $this->reg['phone_code']; }

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
