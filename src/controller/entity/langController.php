<?php

namespace src\controller\entity;

use src\controller\baseController;

class langController extends baseController
{
    use repository\langRepositoryController;
    use verifications\langVerificationController;

    private $table = 'lang';
    private $reg = array(    
                            'id'           => '',
                            'code_2a'      => NULL,
                            'code_3a'      => NULL,
                            'family'       => NULL,
                            'iso_name'     => NULL,
                            'folder'       => NULL,
                            'default'      => '0',
                            'active'       => '0',
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
     * Get lang from his id
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
     * Get lang from code_2a
     *
     */
    public function getRegbyCode2a( $code_2a )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Code_2a ==========> ('.$code_2a.')'.PHP_EOL; fwrite($this->myfile, $txt);
        if ( !$code_2a ) return false;

        $filter = array( 'code_2a' => $code_2a  );
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
    public function setCode2a( $code2a ) { $this->reg['code_2a'] = $code2a;  }
    public function setCode3a( $code3a ) { $this->reg['code_3a'] = $code3a;  }
    public function setFamily( $family ) { $this->reg['family'] = $family;  }
    public function setIsoName( $isoname ) { $this->reg['iso_name'] = $isoname;  }
    public function setFolder( $folder ) { $this->reg['folder'] = $folder;  }
    public function setDefault( $default ) { $this->reg['default'] = $default;  }
    public function setActive( $active ) { $this->reg['active'] = $active;  }

    public function getReg() { return $this->reg; }
    public function getAll( $filter='', $extra='' ) { return $this->db->fetchAll( $this->table, '*', $filter, $extra ); }
    public function getId() { return $this->reg['id']; }
    public function getCode2a() { return $this->reg['code_2a']; }
    public function getCode3a() { return $this->reg['code_3a']; }
    public function getFamily() { return $this->reg['family']; }
    public function getIsoName() { return $this->reg['iso_name']; }
    public function getFolder() { return $this->reg['folder']; }
    public function getDefault() { return $this->reg['default']; }
    public function getActive() { return $this->reg['active']; }

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
        $this->reg['code_2a'] = ( empty($this->reg['code_2a']) )? NULL : $this->reg['code_2a'];
        $this->reg['code_3a'] = ( empty($this->reg['code_3a']) )? NULL : $this->reg['code_3a'];
        $this->reg['family'] = ( empty($this->reg['family']) )? NULL : $this->reg['family'];
        $this->reg['iso_name'] = ( empty($this->reg['iso_name']) )? NULL : $this->reg['iso_name'];
        $this->reg['folder'] = ( empty($this->reg['folder']) )? NULL : $this->reg['folder'];
        $this->reg['default'] = ( empty($this->reg['default']) )? '0' : $this->reg['default'];
        $this->reg['active'] = ( empty($this->reg['active']) )? '0' : $this->reg['active'];
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }
}
