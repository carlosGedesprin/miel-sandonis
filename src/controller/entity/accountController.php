<?php

namespace src\controller\entity;

use \src\controller\baseController;

use DateTime;
use DateTimeZone;

class accountController extends baseController
{
    use repository\accountRepositoryController;
    use verifications\accountVerificationController;

    private $table = 'account';
    private $reg = array(
                            'id'           => '',
                            'account_key'  => NULL,
                            'group'        => '',
                            'main_user'    => NULL,
                            'name'         => NULL,
                            'company'      => NULL,
                            'notifications_email' => NULL,
                            'locale'       => NULL,
                            'address'      => NULL,
                            'post_code'    => NULL,
                            'country'      => NULL,
                            'region'       => NULL,
                            'city'         => NULL,
                            'alt_city'     => NULL,
                            'phone'        => NULL,
                            'vat'          => NULL,
                            'vat_type'     => NULL,
                            'agent'        => NULL,
                            'show_to_staff'          => '1',
                            'allow_staff_use_card'   => '1',
                            'preferred_payment_type' => NULL,
                            'commission_percent'     => NULL,
                            'coupon'       => NULL,
                            'stripe_id'    => NULL,
                            'active'       => '0',
                        );

    private $users;
    private $widgets;

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
     * Get account from his id
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
     * Get account from his account key
     *
     */
    public function getRegbyAccountKey( $account_key )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Account key ==========> ('.$account_key.')'.PHP_EOL; fwrite($this->myfile, $txt);
        if ( !$account_key ) return false;

        $filter = array( 'account_key' => $account_key  );
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
    public function setAccountKey( $account_key ) { $this->reg['account_key'] = $account_key;  }
    public function setGroup( $group ) { $this->reg['group'] = $group;  }
    public function setMainUser( $main_user ) { $this->reg['main_user'] = $main_user;  }
    public function setName( $name ) { $this->reg['name'] = $name;  }
    public function setCompany( $company ) { $this->reg['company'] = $company;  }
    public function setNotificationsEmail( $notifications_email ) { $this->reg['notifications_email'] = $notifications_email;  }
    public function setLocale( $locale ) { $this->reg['locale'] = $locale;  }
    public function setAddress( $address ) { $this->reg['address'] = $address;  }
    public function setPostCode( $post_code ) { $this->reg['post_code'] = $post_code;  }
    public function setCountry( $country ) { $this->reg['country'] = $country;  }
    public function setRegion( $region ) { $this->reg['region'] = $region;  }
    public function setCity( $city ) { $this->reg['city'] = $city;  }
    public function setAltCity( $alt_city ) { $this->reg['alt_city'] = $alt_city;  }
    public function setPhone( $phone ) { $this->reg['phone'] = $phone;  }
    public function setVat( $vat ) { $this->reg['vat'] = $vat;  }
    public function setVatType( $vat_type ) { $this->reg['vat_type'] = $vat_type;  }
    public function setAgent( $agent ) { $this->reg['agent'] = $agent;  }
    public function setShowToStaff( $show_to_staff ) { $this->reg['show_to_staff'] = $show_to_staff; }
    public function setAllowStaffUseCard( $allow_staff_use_card ) { $this->reg['allow_staff_use_card'] = $allow_staff_use_card; }
    public function setPreferredPaymentType( $preferred_payment_type ) { $this->reg['preferred_payment_type'] = $preferred_payment_type;  }
    public function setCommissionPercent( $commission_percent ) { $this->reg['commission_percent'] = $commission_percent;  }
    public function setCoupon( $coupon ) { $this->reg['coupon'] = $coupon;  }
    public function setStripeId( $stripe_id ) { $this->reg['stripe_id'] = $stripe_id;  }
    public function setActive( $active ) { $this->reg['active'] = $active;  }

    public function getReg() { return $this->reg; }
    public function getAll( $filter='', $extra='' ) { return $this->db->fetchAll( $this->table, '*', $filter, $extra ); }
    public function getId() { return $this->reg['id']; }
    public function getAccountKey() { return $this->reg['account_key']; }
    public function getGroup() { return $this->reg['group']; }
    public function getMainUser() { return $this->reg['main_user']; }
    public function getName() { return $this->reg['name']; }
    public function getCompany() { return $this->reg['company']; }
    public function getNotificationsEmail() { return $this->reg['notifications_email']; }
    public function getLocale() { return $this->reg['locale']; }
    public function getAddress() { return $this->reg['address']; }
    public function getPostCode() { return $this->reg['post_code']; }
    public function getCountry() { return $this->reg['country']; }
    public function getRegion() { return $this->reg['region']; }
    public function getCity() { return $this->reg['city']; }
    public function getAltCity() { return $this->reg['alt_city']; }
    public function getPhone() { return $this->reg['phone']; }
    public function getVat() { return $this->reg['vat']; }
    public function getVatType() { return $this->reg['vat_type']; }
    public function getAgent() { return $this->reg['agent']; }
    public function getShowToStaff() { return $this->reg['show_to_staff']; }
    public function getAllowStaffUseCard() { return $this->reg['allow_staff_use_card']; }
    public function getPreferredPaymentType() { return $this->reg['preferred_payment_type']; }
    public function getCommissionPercent() { return $this->reg['commission_percent']; }
    public function getCoupon() { return $this->reg['coupon']; }
    public function getStripeId() { return $this->reg['stripe_id'];  }
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
        $this->reg['account_key'] = ( empty($this->reg['account_key']) )? NULL : $this->reg['account_key'];
        $this->reg['notifications_email'] = ( empty($this->reg['notifications_email']) )? NULL : strtolower( $this->reg['notifications_email'] );
        $this->reg['coupon'] = ( empty($this->reg['coupon']) )? NULL : $this->reg['coupon'];

        $this->reg['main_user'] = ( empty($this->reg['main_user']) )? NULL : $this->reg['main_user'];
        $this->reg['show_to_staff'] = ( empty( $this->reg['show_to_staff'] ) )? '0' : $this->reg['show_to_staff'];
        $this->reg['allow_staff_use_card'] = ( empty( $this->reg['allow_staff_use_card'] ) )? '0' : $this->reg['allow_staff_use_card'];
        $this->reg['preferred_payment_type'] = ( $this->reg['preferred_payment_type'] == '' )? '3' : $this->reg['preferred_payment_type'];
        $this->reg['active'] = ( empty($this->reg['active']) )? '0' : $this->reg['active'];
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }
}
