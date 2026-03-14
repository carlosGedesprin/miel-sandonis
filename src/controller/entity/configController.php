<?php

namespace src\controller\entity;

use \src\controller\baseController;

use DateTime;
use DateTimeZone;


class configController extends baseController
{
    private $table = 'config';
    private $reg = array(
                            'id'             => '',
                            'config_name'    => '',
                            'config_value'   => '',
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
     * Get config from his id
     *
     */
    public function getRegbyId( $id )
    {
//$txt = __FUNCTION__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Id ==========> ('.$id.')'.PHP_EOL; fwrite($this->myfile, $txt);
        $filter = array( 'id' => $id  );
        return $this->getRegFromDB( $filter );
    }

    /**
     *
     * Get config value by his config_name
     *
     */
    public function getRegbyName( $config_name )
    {
//$txt = __FUNCTION__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Id ==========> ('.$id.')'.PHP_EOL; fwrite($this->myfile, $txt);
        $filter = array( 'config_name' => $config_name  );
        return $this->getRegFromDB( $filter );
    }

    /**
     *
     * Get reg
     *
     */
    private function getRegFromDB( $filter )
    {
//$txt = __FUNCTION__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Filter ==========> ('.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($filter(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        if ( $item = $this->db->fetchOne( $this->table, '*', $filter ) )
        {
            $this->reg = array_merge( $this->reg, $item );

            $this->loadSpecialFields();

//$txt = 'reg found==========> ('.$this->getId().')'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
            return true;
        }
        else
        {
//$txt = 'reg NOT found ==========> '.PHP_EOL; fwrite($this->myfile, $txt);
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
//$txt = __FUNCTION__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Table ==> ('.$this->table.')'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->reg, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $this->setSpecialFields();

        if ( $this->reg['id'] != '' && $this->reg['id'] != '0' )
        {
            $this->db->updateArray( $this->table, 'id', $this->reg['id'], $this->reg );
//$txt = 'reg updated ==> NOT ORL'.PHP_EOL; fwrite($this->myfile, $txt);
            
        }
        else
        {
            unset( $this->reg['id'] );
            $this->setId( $this->db->insertArray( $this->table, $this->reg ) );
//$txt = 'reg inserted ==> NOT ORL'.PHP_EOL; fwrite($this->myfile, $txt);
        }

        $this->loadSpecialFields();

        return $this->getId();
    }

    /**
     *
     * Delete this record
     *
     */
    public function delete()
    {
//$txt = __FUNCTION__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Table ==========> ('.$this->table.')'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->reg, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        if ( $this->reg['id'] != '' && $this->reg['id'] != '0' )
        {
            $this->db->delete( $this->table, 'id', $this->getId() );
            return true;
        }
        return false;
    }

    // Setters
    public function setId( $id ) { $this->reg['id'] = $id;  }
    public function setConfigName( $configname ) { $this->reg['config_name'] = $configname;  }
    public function setConfigValue( $configvalue ) { $this->reg['config_value'] = $configvalue;  }

    
     // Getters
     public function getReg() { return $this->reg; }
     public function getId() { return $this->reg['id']; }
     public function getConfigName() { return $this->reg['config_name']; }
     public function getConfigValue() { return $this->reg['config_value']; }

    /**
     *
     * Change special fields after load from database
     */
    private function loadSpecialFields()
    {
//$txt = 'util_'.__FUNCTION__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     *
     * Change special fields before save to database
     */
    private function setSpecialFields()
    {
//$txt = 'util_'.__FUNCTION__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }
}
