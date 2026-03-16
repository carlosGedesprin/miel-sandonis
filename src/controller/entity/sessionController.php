<?php

namespace src\controller\entity;

use \src\controller\baseController;

use DateTime;
use DateTimeZone;

class sessionController extends baseController
{
    private $table = 'session';
    private $reg = array(
                            'sess_id'           => '',
                            'sess_data'         => '',
                            'sess_time'         => '',
                            'sess_lifetime'     => '',
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
     * Get session from his id
     *
     */
    public function getRegbyId( $id )
    {
//$txt = __FUNCTION__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Id ==========> ('.$id.')'.PHP_EOL; fwrite($this->myfile, $txt);
        if ( $item = $this->db->fetchOne( $this->table, '*', [ 'id' => $id ] ) )
        {
            $this->reg = array_merge( $this->reg, $item );
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
        if ( $this->reg['id'] != '' )
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
//$txt = 'reg deleted ==========> '.$this->reg['id'].PHP_EOL; fwrite($this->myfile, $txt);
            return true;
        }
        return false;
    }

   
    // Setters
    public function setId( $id ) { $this->reg['sess_id'] = $id;  }
    public function setSessData( $sessdata ) { $this->reg['sess_data'] = $sessdata;  }
    public function setSessTime( $sesstime ) { $this->reg['sess_time'] = $sesstime;  }
    public function setSessLifeTime( $sesslifetime ) { $this->reg['sess_lifetime'] = $sesslifetime;  }
   
     // Getters
     public function getReg() { return $this->reg; }
     public function getId() { return $this->reg['sess_id']; }
     public function getSessData() { return $this->reg['sess_data']; }
     public function getSessTime() { return $this->reg['sess_time']; }
     public function getSessLifeTime() { return $this->reg['sess_lifetime']; }
}

 