<?php

namespace src\controller\entity;

use src\controller\baseController;

use DateTime;
use DateTimeZone;

class ragController extends baseController
{
    use repository\ragRepositoryController;
    use verifications\ragVerificationController;

    private $table = 'rag';
    private $reg = array(
                        'id'              => '',
                        'rag_key'         => NULL,
                        'name'            => NULL,
                        'account'         => NULL,
                        'billing_account' => NULL,
                        'product_setup'   => NULL,
                        'product_renewal' => NULL,
                        'price_setup'     => NULL,
                        'price_renewal'   => NULL,
                        'coupon'          => NULL,
                        'date_reg'        => NULL,
                        'auto_renew'      => '1',
                        'date_start'      => NULL,
                        'date_end'        => NULL,
                        'agent'           => NULL,
                        'server'          => NULL,
                        'address'         => NULL,
                        'folder'          => NULL,
                        'username'        => NULL,
                        'password'        => NULL,
                        'active'          => '0',
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
     * Get reg from his id
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
     * Get reg from his key
     *
     */
    public function getRegbyRagKey( $key )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Key ==========> ('.$key.')'.PHP_EOL; fwrite($this->myfile, $txt);
        if ( !$key ) return false;

        $filter = array( 'rag_key' => $key  );
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
    public function setId( $id ) { $this->reg['id'] = $id; }
    public function setRagKey( $rag_key ) { $this->reg['rag_key'] = $rag_key; }
    public function setName( $name ) { $this->reg['name'] = $name; }
    public function setAccount( $account ) { $this->reg['account'] = $account; }
    public function setBillingAccount( $billing_account ) { $this->reg['billing_account'] = $billing_account; }
    public function setProductSetup( $product_setup ) { $this->reg['product_setup'] = $product_setup; }
    public function setProductRenewal( $product_renewal ) { $this->reg['product_renewal'] = $product_renewal; }
    public function setPriceSetup( $price_setup ) { $this->reg['price_setup'] = $price_setup; }
    public function setPriceRenewal( $price_renewal ) { $this->reg['price_renewal'] = $price_renewal; }
    public function setCoupon( $coupon ) { $this->reg['coupon'] = $coupon; }
    public function setDateReg( $datereg ) { $this->reg['date_reg'] = $datereg; }
    public function setAutoRenew( $auto_renew ) { $this->reg['auto_renew'] = $auto_renew;  }
    public function setDateStart( $datestart ) { $this->reg['date_start'] = $datestart; }
    public function setDateEnd( $dateend ) { $this->reg['date_end'] = $dateend; }
    public function setAgent( $agent ) { $this->reg['agent'] = $agent; }
    public function setServer( $server ) { $this->reg['server'] = $server; }
    public function setAddress( $address ) { $this->reg['address'] = $address; }
    public function setFolder( $folder ) { $this->reg['folder'] = $folder; }
    public function setUsername( $username ) { $this->reg['username'] = $username; }
    public function setPassword( $password ) { $this->reg['password'] = $password; }
    public function setActive( $active ) { $this->reg['active'] = $active; }

    public function getReg() { return $this->reg; }
    public function getAll( $filter='', $extra='' ) { return $this->db->fetchAll( $this->table, '*', $filter, $extra ); }
    public function getId() { return $this->reg['id']; }
    public function getRagKey() { return $this->reg['rag_key']; }
    public function getName() { return $this->reg['name']; }
    public function getAccount() { return $this->reg['account']; }
    public function getBillingAccount() { return $this->reg['billing_account']; }
    public function getProductSetup() { return $this->reg['product_setup']; }
    public function getProductRenewal() { return $this->reg['product_renewal']; }
    public function getPriceSetup() { return $this->reg['price_setup']; }
    public function getPriceRenewal() { return $this->reg['price_renewal']; }
    public function getCoupon() { return $this->reg['coupon']; }
    public function getDateReg() { return $this->reg['date_reg']; }
    public function getAutoRenew() { return $this->reg['auto_renew']; }
    public function getDateStart() { return $this->reg['date_start']; }
    public function getDateEnd() { return $this->reg['date_end']; }
    public function getAgent() { return $this->reg['agent']; }
    public function getServer() { return $this->reg['server']; }
    public function getAddress() { return $this->reg['address']; }
    public function getFolder() { return $this->reg['folder']; }
    public function getUsername() { return $this->reg['username']; }
    public function getPassword() { return $this->reg['password']; }
    public function getActive() { return $this->reg['active']; }

    /**
     *
     * Change special fields after load from database
     */
    private function loadSpecialFields()
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $this->reg['date_reg'] = ( empty($this->reg['date_reg']) )? NULL : DateTime::createFromFormat('Y-m-d H:i:s', $this->reg['date_reg'], new DateTimeZone($this->session->config['time_zone']));
        $this->reg['date_start'] = ( empty($this->reg['date_start']) )? NULL : DateTime::createFromFormat('Y-m-d', $this->reg['date_start'], new DateTimeZone($this->session->config['time_zone']));
        $this->reg['date_end'] = ( empty($this->reg['date_end']) )? NULL : DateTime::createFromFormat('Y-m-d', $this->reg['date_end'], new DateTimeZone($this->session->config['time_zone']));
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     *
     * Change special fields before save to database
     */
    private function setSpecialFields()
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $this->reg['account'] = ( empty($this->reg['account']) )? NULL : $this->reg['account'];
        $this->reg['billing_account'] = ( empty($this->reg['billing_account']) )? NULL : $this->reg['billing_account'];

        $this->reg['product_setup'] = ( empty($this->reg['product_setup']) )? NULL : $this->reg['product_setup'];
        $this->reg['product_renewal'] = ( empty($this->reg['product_renewal']) )? NULL : $this->reg['product_renewal'];

        $this->reg['price_setup'] = ( empty($this->reg['price_setup']) )? '0' : str_replace( ',', '', $this->reg['price_setup']);
        $this->reg['price_renewal'] = ( empty($this->reg['price_renewal']) )? '0' : str_replace( ',', '', $this->reg['price_renewal']);

        $this->reg['coupon'] = ( empty($this->reg['coupon']) )? NULL : $this->reg['coupon'];

        $this->reg['date_reg'] = ( empty($this->reg['date_reg']) )? NULL : $this->reg['date_reg']->format('Y-m-d H:i:s');
        $this->reg['date_start'] = ( empty($this->reg['date_start']) )? NULL : $this->reg['date_start']->format('Y-m-d');
        $this->reg['date_end'] = ( empty($this->reg['date_end']) )? NULL : $this->reg['date_end']->format('Y-m-d');

        $this->reg['server'] = ( empty($this->reg['server']) )? NULL : $this->reg['server'];
        $this->reg['folder'] = ( empty($this->reg['folder']) )? NULL : $this->reg['folder'];

        $this->reg['active'] = ( empty($this->reg['active']) )? '0' : $this->reg['active'];
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }
}