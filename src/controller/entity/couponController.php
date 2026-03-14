<?php

namespace src\controller\entity;

use src\controller\baseController;

use DateTime;
use DateTimeZone;

class couponController extends baseController
{
    use repository\couponRepositoryController;
    use verifications\couponVerificationController;

    private $table = 'coupon';
    private $reg = array(
                            'id'            => '',
                            'name'          => NULL,
                            'code'          => NULL,
                            'discount'      => '0',
                            'discount_type' => NULL,
                            'period'        => NULL,
                            'num_period'    => NULL,
                            'validity_date_start' => NULL,
                            'validity_date_end'   => NULL,
                            'agent'         => NULL,
                            'integrator'    => NULL,
                            'commission_percent'  => '0',
                            'plan'          => NULL,
                            'active'        => '0',
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
     * Get coupon from his id
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
     * Get coupon from his agent
     *
     */
    public function getRegbyAgent( $agent )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Agent ==========> ('.$agent.')'.PHP_EOL; fwrite($this->myfile, $txt);
        if ( !$agent ) return false;

        $filter = array( 'agent' => $agent );
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $this->getRegFromDB( $filter );
    }

    /**
     *
     * Get coupon from his integrator
     *
     */
    public function getRegbyIntegrator( $integrator )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Integrator ==========> ('.$integrator.')'.PHP_EOL; fwrite($this->myfile, $txt);
        if ( !$integrator ) return false;

        $filter = array( 'integrator' => $integrator );
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $this->getRegFromDB( $filter );
    }

    /**
     *
     * Get coupon from his code
     *
     */
    public function getRegbyCode( $code )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Code ==========> ('.$code.')'.PHP_EOL; fwrite($this->myfile, $txt);
        if ( !$code ) return false;

        $filter = array( 'code' => $code  );
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
    public function setName( $name ) { $this->reg['name'] = $name;  }
    public function setCode( $code ) { $this->reg['code'] = $code;  }
    public function setDiscount( $discount ) { $this->reg['discount'] = $discount;  }
    public function setAgent( $agent ) { $this->reg['agent'] = $agent;  }
    public function setIntegrator( $integrator ) { $this->reg['integrator'] = $integrator;  }
    public function setCommissionPercent( $commission_percent ) { $this->reg['commission_percent'] = $commission_percent;  }
    public function setDiscountType( $discount_type ) { $this->reg['discount_type'] = $discount_type;  }
    public function setPeriod( $period ) { $this->reg['period'] = $period;  }
    public function setNumPeriod( $num_period ) { $this->reg['num_period'] = $num_period;  }
    public function setValidityDateStart( $validity_date_start ) { $this->reg['validity_date_start'] = $validity_date_start; }
    public function setValidityDateEnd( $validity_date_end ) { $this->reg['validity_date_end'] = $validity_date_end; }
    public function setPlan( $plan ) { $this->reg['plan'] = $plan;  }
    public function setActive( $active ) { $this->reg['active'] = $active;  }

    public function getReg() { return $this->reg; }
    public function getAll( $filter='', $extra='' ) { return $this->db->fetchAll( $this->table, '*', $filter, $extra ); }
    public function getId() { return $this->reg['id']; }
    public function getName() { return $this->reg['name']; }
    public function getCode() { return $this->reg['code']; }
    public function getDiscount() { return $this->reg['discount']; }
    public function getAgent() { return $this->reg['agent']; }
    public function getIntegrator() { return $this->reg['integrator']; }
    public function getCommissionPercent() { return $this->reg['commission_percent']; }
    public function getDiscountType() { return $this->reg['discount_type']; }
    public function getPeriod() { return $this->reg['period']; }
    public function getNumPeriod() { return $this->reg['num_period']; }
    public function getValidityDateStart() { return $this->reg['validity_date_start']; }
    public function getValidityDateEnd() { return $this->reg['validity_date_end']; }
    public function getPlan() { return $this->reg['plan']; }
    public function getActive() { return $this->reg['active']; }

    /**
     *
     * Change special fields after load from database
     */
    private function loadSpecialFields()
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        if ( $this->getPlan() === NULL ) $this->setPlan( '0' );
        $this->reg['validity_date_start'] = ( $this->reg['validity_date_start'] == '' )? NULL : DateTime::createFromFormat('Y-m-d', $this->reg['validity_date_start'], new DateTimeZone($this->session->config['time_zone']));
        $this->reg['validity_date_end'] = ( $this->reg['validity_date_end'] == '' )? NULL : DateTime::createFromFormat('Y-m-d', $this->reg['validity_date_end'], new DateTimeZone($this->session->config['time_zone']));
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     *
     * Change special fields before save to database
     */
    private function setSpecialFields()
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $this->reg['validity_date_start'] = ( $this->reg['validity_date_start'] == '' )? NULL : $this->reg['validity_date_start']->format('Y-m-d');
        $this->reg['validity_date_end'] = ( $this->reg['validity_date_end'] == '' )? NULL : $this->reg['validity_date_end']->format('Y-m-d');

        $this->reg['discount'] = ( empty($this->reg['discount']) )? '0' : $this->reg['discount'];
        $this->reg['commission_percent'] = ( empty($this->reg['commission_percent']) )? '0' : $this->reg['commission_percent'];
        $this->reg['active'] = ( empty($this->reg['active']) )? '0' : $this->reg['active'];

        $this->reg['code'] = ( empty($this->reg['code']) )? NULL : $this->reg['code'];
        $this->reg['agent'] = ( empty($this->reg['agent']) )? NULL : $this->reg['agent'];
        $this->reg['integrator'] = ( empty($this->reg['integrator']) )? NULL : $this->reg['integrator'];
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }
}
