<?php

namespace src\controller\entity;

use src\controller\baseController;

class productController extends baseController
{
    use repository\productRepositoryController;

    private $table = 'product';
    private $reg = array(
                            'id'                      => '',
                            'product_type'            => NULL,
                            'name'                    => NULL,
                            'period_demo'             => NULL,
                            'num_period_demo'         => NULL,
                            'period'                  => NULL,
                            'num_period'              => NULL,
                            'period_grace'            => NULL,
                            'num_period_grace'        => NULL,
                            'price'                   => '0',
                            'payed_class'             => NULL,
                            'payed_method'            => NULL,
                            'generate_commission'     => '0',
                            'show_in_cp'              => '0',
                            'active'                  => '0',
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
     * Get product from his id
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
    public function setProductType( $product_type ) { $this->reg['product_type'] = $product_type;  }
    public function setName( $name ) { $this->reg['name'] = $name;  }
    public function setPeriodDemo( $period_demo ) { $this->reg['period_demo'] = $period_demo;  }
    public function setNumPeriodDemo( $num_period_demo ) { $this->reg['num_period_demo'] = $num_period_demo;  }
    public function setPeriod( $period ) { $this->reg['period'] = $period;  }
    public function setNumPeriod( $num_period ) { $this->reg['num_period'] = $num_period;  }
    public function setPeriodGrace( $period_grace ) { $this->reg['period_grace'] = $period_grace;  }
    public function setNumPeriodGrace( $num_period_grace ) { $this->reg['num_period_grace'] = $num_period_grace;  }
    public function setPrice( $price ) { $this->reg['price'] = $price;  }
    public function setPayedClass( $payed_class ) { $this->reg['payed_class'] = $payed_class; }
    public function setPayedMethod( $payed_method ) { $this->reg['payed_method'] = $payed_method; }
    public function setGenerateCommission( $generate_commission ) { $this->reg['generate_commission'] = $generate_commission;  }
    public function setShowInCP( $show_in_cp ) { $this->reg['show_in_cp'] = $show_in_cp;  }
    public function setActive( $active ) { $this->reg['active'] = $active;  }

    public function getReg() { return $this->reg; }
    public function getAll( $filter='', $extra='' ) { return $this->db->fetchAll( $this->table, '*', $filter, $extra ); }
    public function getId() { return $this->reg['id']; }
    public function getProductType() { return $this->reg['product_type']; }
    public function getName() { return $this->reg['name']; }
    public function getPeriodDemo() { return $this->reg['period_demo']; }
    public function getNumPeriodDemo() { return $this->reg['num_period_demo']; }
    public function getPeriod() { return $this->reg['period']; }
    public function getNumPeriod() { return $this->reg['num_period']; }
    public function getPeriodGrace() { return $this->reg['period_grace']; }
    public function getNumPeriodGrace() { return $this->reg['num_period_grace']; }
    public function getPrice() { return $this->reg['price']; }
    public function getPayedClass() { return $this->reg['payed_class']; }
    public function getPayedMethod() { return $this->reg['payed_method']; }
    public function getGenerateCommission() { return $this->reg['generate_commission']; }
    public function getShowInCP() { return $this->reg['show_in_cp']; }
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
        $this->reg['product_type'] = ( empty($this->reg['product_type']) )? NULL : $this->reg['product_type'];
        $this->reg['period_demo'] = ( empty( $this->reg['period_demo']) )? NULL : $this->reg['period_demo'];
        $this->reg['period'] = ( empty( $this->reg['period']) )? NULL : $this->reg['period'];
        $this->reg['period_grace'] = ( empty( $this->reg['period_grace']) )? NULL : $this->reg['period_grace'];

        $this->reg['price'] = ( empty($this->reg['price']) )? '0' : str_replace( ',', '', $this->reg['price']);

        $this->reg['generate_commission'] = ( empty($this->reg['generate_commission']) )? '0' : $this->reg['generate_commission'];
        $this->reg['show_in_cp'] = ( empty($this->reg['show_in_cp']) )? '0' : $this->reg['show_in_cp'];
        $this->reg['active'] = ( empty($this->reg['active']) )? '0' : $this->reg['active'];
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }
}