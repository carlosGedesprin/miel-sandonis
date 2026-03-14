<?php

namespace src\controller\entity;

use src\controller\baseController;

use DateTime;
use DateTimeZone;

class cronController extends baseController
{
    use repository\cronRepositoryController;

    private $table = 'cron';
    private $reg = array(
                            'id'           => '',
                            'process'      => NULL,
                            'run'          => NULL,
                            'periodicity'  => NULL,
                            'size'         => NULL,
                            'delaytime'    => NULL,
                            'ordinal'      => NULL,
                            'last_run'     => NULL,
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
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    /**
     *
     * Get cron from his id
     *
     */
    public function getRegbyId( $id )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Id ==========> ('.$id.')'.PHP_EOL; fwrite($this->myfile, $txt);
        if ( !$id ) return false;

        $filter = array( 'id' => $id  );
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $this->getRegFromDB( $filter );
    }
    /**
     *
     * Get cron from his process
     *
     */
    public function getRegbyProcess( $process )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Process ==========> ('.$process.')'.PHP_EOL; fwrite($this->myfile, $txt);
        if ( !$process ) return false;

        $filter = array( 'process' => $process  );
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $this->getRegFromDB( $filter );
    }

    /**
     *
     * Get reg
     *
     */
    private function getRegFromDB( $filter )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Filter ==========> ('.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($filter, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        if ( $item = $this->db->fetchOne( $this->table, '*', $filter ) )
        {
            $this->reg = array_merge( $this->reg, $item );

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
    public function persistORL()
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Table ==> ('.$this->table.') User ==> ('.$this->user.')'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->reg, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $this->setSpecialFields();

        if ( $this->reg['id'] != '' && $this->reg['id'] != '0' )
        {
            $this->db->updateArrayORL( $this->table, $this->user, 'id', $this->reg['id'], $this->reg );
//$txt = 'reg updated ==> ORL'.PHP_EOL; fwrite($this->myfile, $txt);
        }
        else
        {
            unset( $this->reg['id'] );
            $this->setId( $this->db->insertArrayORL( $this->table, $this->user, $this->reg ) );
        }

        $this->loadSpecialFields();

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $this->getId();
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

        if ( $this->reg['id'] != '' && $this->reg['id'] != '0' )
        {
            $this->db->updateArray( $this->table, 'id', $this->reg['id'], $this->reg );
//$txt = 'reg updated ==> ORL'.PHP_EOL; fwrite($this->myfile, $txt);
        }
        else
        {
            unset( $this->reg['id'] );
            $this->setId( $this->db->insertArray( $this->table, $this->reg ) );
        }

        $this->loadSpecialFields();

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $this->getId();
    }

    /**
     *
     * Delete this record
     *
     */
    public function deleteORL()
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Table ==========> ('.$this->table.')'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->reg, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        if ( $this->reg['id'] != '' && $this->reg['id'] != '0' )
        {
            $this->db->deleteORL( $this->table, $this->user, 'id', $this->getId() );
//$txt = 'reg deleted ==========> '.$this->reg['id'].PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
            return true;
        }
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return false;
    }

    /**
     *
     * Delete this record
     *
     */
    public function delete()
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Table ==========> ('.$this->table.')'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->reg, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        if ( $this->reg['id'] != '' && $this->reg['id'] != '0' )
        {
            $this->db->delete( $this->table, 'id', $this->getId() );
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
            return true;
        }
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return false;
    }

    public function setReg( $reg ) { $this->reg = array_merge( $this->reg, $reg );  }
    public function setId( $id ) { $this->reg['id'] = $id;  }
    public function setProcess( $process ) { $this->reg['process'] = $process;  }
    public function setRun( $run ) { $this->reg['run'] = $run;  }
    public function setPeriodicity( $periodicity ) { $this->reg['periodicity'] = $periodicity;  }
    public function setSize( $size ) { $this->reg['size'] = $size;  }
    public function setDelayTime( $delaytime ) { $this->reg['delaytime'] = $delaytime;  }
    public function setOrdinal( $ordinal ) { $this->reg['ordinal'] = $ordinal;  }
    public function setLastRun( $lastrun ) { $this->reg['last_run'] = $lastrun;  }

    public function getReg() { return $this->reg; }
    public function getAll( $filter='', $extra='' ) { return $this->db->fetchAll( $this->table, '*', $filter, $extra ); }
    public function getId() { return $this->reg['id']; }
    public function getProcess() { return $this->reg['process']; }
    public function getRun() { return $this->reg['run']; }
    public function getPeriodicity() { return $this->reg['periodicity']; }
    public function getSize() { return $this->reg['size']; }
    public function getDelaytime() { return $this->reg['delaytime']; }
    public function getOrdinal() { return $this->reg['ordinal']; }
    public function getLastRun() { return $this->reg['last_run']; }

    /**
     *
     * Change special fields after load from database
     */
    private function loadSpecialFields()
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        $this->reg['last_run'] = ( empty($this->reg['last_run']) )? NULL : DateTime::createFromFormat('Y-m-d H:i:s', $this->reg['last_run'], new DateTimeZone($this->session->config['time_zone']));
//fwrite($this->myfile, print_r($this->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     *
     * Change special fields before save to database
     */
    private function setSpecialFields()
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Reg last_run ('.$this->reg['last_run'].')'.PHP_EOL; fwrite($this->myfile, $txt);
        $this->reg['last_run'] = ( empty($this->reg['last_run']) )? NULL : $this->reg['last_run']->format('Y-m-d H:i:s');
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }
}
