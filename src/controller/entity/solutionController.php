<?php

namespace src\controller\entity;

use \src\controller\baseController;

class solutionController extends baseController
{
    use repository\solutionRepositoryController;
    use verifications\solutionVerificationController;

    private $table = 'solution';
    private $reg = array(
                            'id'           => NULL,
                            'key'          => NULL,
                            'name'         => NULL,
                            'slug'         => NULL,
                            'title'        => NULL,
                            'subtitle'     => NULL,
                            'problem'      => NULL,
                            'solution'     => NULL,
                            'image'        => NULL,
                            'workflows'    => array(),
                            'active'       => '1',
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
     * Get solution from his id
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
     * Get solution from his key
     *
     */
    public function getRegbyKey( $key )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Solution key ==========> ('.$key.')'.PHP_EOL; fwrite($this->myfile, $txt);
        $filter = array( 'key' => $key );
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $this->getRegFromDB( $filter );
    }

    /**
     *
     * Get solution from his name
     *
     */
    public function getRegbyName( $name )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Solution name ==========> ('.$name.')'.PHP_EOL; fwrite($this->myfile, $txt);
        $filter = array( 'name' => $name );
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $this->getRegFromDB( $filter );
    }

    /**
     *
     * Get solution from his slug
     *
     */
    public function getRegbySlug( $slug )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Solution slug ==========> ('.$slug.')'.PHP_EOL; fwrite($this->myfile, $txt);
        $filter = array( 'slug' => $slug );
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
    public function setKey( $key ) { $this->reg['key'] = $key;  }
    public function setName( $name ) { $this->reg['name'] = $name;  }
    public function setSlug( $slug ) { $this->reg['slug'] = $slug;  }
    public function setTitle( $title ) { $this->reg['title'] = $title;  }
    public function setSubTitle( $subtitle ) { $this->reg['subtitle'] = $subtitle;  }
    public function setProblem( $problem ) { $this->reg['problem'] = $problem;  }
    public function setSolution( $solution ) { $this->reg['solution'] = $solution;  }
    public function setImage( $image ) { $this->reg['image'] = $image;  }
    public function setWorkflow( $workflow ) { $this->reg['workflows'][] = $workflow; }
    public function setActive( $active ) { $this->reg['active'] = $active;  }

    public function cleanWorkflows() { $this->reg['workflows'] = array(); }

    public function getReg() { return $this->reg; }
    public function getAll( $filter='', $extra='' ) { return $this->db->fetchAll( $this->table, '*', $filter, $extra ); }
    public function getId() { return $this->reg['id']; }
    public function getKey() { return $this->reg['key']; }
    public function getName() { return $this->reg['name']; }
    public function getSlug() { return $this->reg['slug']; }
    public function getTitle() { return $this->reg['title']; }
    public function getSubTitle() { return $this->reg['subtitle']; }
    public function getProblem() { return $this->reg['problem']; }
    public function getSolution() { return $this->reg['solution']; }
    public function getImage() { return $this->reg['image']; }
    public function getWorkflows() { return $this->reg['workflows']; }
    public function getActive() { return $this->reg['active']; }

    /**
     *
     * Change special fields after load from database
     */
    private function loadSpecialFields()
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $this->reg['workflows'] = ( empty($this->reg['workflows']) )? array() : explode( ',', $this->reg['workflows'] );
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     *
     * Change special fields before save to database
     */
    private function setSpecialFields()
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $this->reg['workflows'] = ( sizeof( $this->reg['workflows'] ) == 0 )? NULL : implode( ',', $this->reg['workflows'] );
        $this->reg['active'] = ( empty($this->reg['active']) )? '0' : $this->reg['active'];
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }
}
