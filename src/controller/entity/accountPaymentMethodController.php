<?php

namespace src\controller\entity;

use src\controller\baseController;

use DateTime;
use DateTimeZone;

class accountPaymentMethodController extends baseController
{
    use repository\accountPaymentMethodRepositoryController;

    private $table = 'account_payment_method';
    private $reg = array(
                            'id'           => '',
                            'key'          => NULL,
                            'account'      => NULL,
                            'name'         => NULL,
                            'payment_type' => NULL,
                            'IBAN'         => NULL,
                            'object_id'    => NULL,
                            'object'       => NULL,
                            'brand'        => NULL,
                            'country'      => NULL,
                            'name_on_card' => NULL,
                            'last_4'       => NULL,
                            'exp_month'    => NULL,
                            'exp_year'     => NULL,
                            'cvc_check'    => NULL,
                            'funding'      => NULL,
                            'preferred'    => '0',
                            'active'       => '1',
                        );

    private $websites;

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
     * Get account payment details from his id
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
     * Get account payment details from his key
     *
     */
    public function getRegbyKey( $key )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Key ==========> ('.$key.')'.PHP_EOL; fwrite($this->myfile, $txt);
        if ( !$key ) return false;

        $filter = array( 'key' => $key  );
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
    public function setAccount( $account ) { $this->reg['account'] = $account; }
    public function setName( $name ) { $this->reg['name'] = $name; }
    public function setPaymentType( $payment_type ) { $this->reg['payment_type'] = $payment_type; }
    public function setIBAN( $IBAN ) { $this->reg['IBAN'] = $IBAN; }
    public function setObjectId( $object_id ) { $this->reg['object_id'] = $object_id; }
    public function setObject( $object ) { $this->reg['object'] = $object; }
    public function setBrand( $brand ) { $this->reg['brand'] = $brand; }
    public function setCountry( $country ) { $this->reg['country'] = $country; }
    public function setNameOnCard( $name_on_card ) { $this->reg['name_on_card'] = $name_on_card; }
    public function setLast4( $last_4 ) { $this->reg['last_4'] = $last_4; }
    public function setExpMonth( $exp_month ) { $this->reg['exp_month'] = $exp_month; }
    public function setExpYear( $exp_year ) { $this->reg['exp_year'] = $exp_year; }
    public function setCVCCheck( $cvc_check ) { $this->reg['cvc_check'] = $cvc_check; }
    public function setFunding( $funding ) { $this->reg['funding'] = $funding; }
    public function setPreferred( $preferred ) { $this->reg['preferred'] = $preferred; }
    public function setActive( $active ) { $this->reg['active'] = $active; }

    public function getReg() { return $this->reg; }
    public function getAll( $filter='', $extra='' ) { return $this->db->fetchAll( $this->table, '*', $filter, $extra ); }
    public function getId() { return $this->reg['id']; }
    public function getKey() { return $this->reg['key']; }
    public function getAccount() { return $this->reg['account']; }
    public function getName() { return $this->reg['name']; }
    public function getPaymentType() { return $this->reg['payment_type']; }
    public function getIBAN() { return $this->reg['IBAN']; }
    public function getObjectId() { return $this->reg['object_id']; }
    public function getObject() { return $this->reg['object']; }
    public function getBrand() { return $this->reg['brand']; }
    public function getCounty() { return $this->reg['country']; }
    public function getNameOnCard() { return $this->reg['name_on_card']; }
    public function getLast4() { return $this->reg['last_4']; }
    public function getExpMonth() { return $this->reg['exp_month']; }
    public function getExpYear() { return $this->reg['exp_year']; }
    public function getCVCCheck() { return $this->reg['cvc_check']; }
    public function getFunding() { return $this->reg['funding']; }
    public function getPreferred() { return $this->reg['preferred']; }
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
        $this->reg['key'] = ( empty($this->reg['key']) )? NULL : $this->reg['key'];
        $this->reg['account'] = ( empty($this->reg['account']) )? NULL : $this->reg['account'];
        $this->reg['payment_type'] = ( empty($this->reg['payment_type']) )? NULL : $this->reg['payment_type'];
        $this->reg['preferred'] = ( empty($this->reg['preferred']) )? '0' : $this->reg['preferred'];
        $this->reg['active'] = ( empty($this->reg['active']) )? '0' : $this->reg['active'];
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }
}