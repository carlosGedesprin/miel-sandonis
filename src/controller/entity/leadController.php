<?php

namespace src\controller\entity;

use \src\controller\baseController;

class leadController extends baseController
{
    use repository\leadRepositoryController;
    use verifications\leadVerificationController;

    private $table = 'lead';
    private $reg = array(
                            'id'           => '',
                            'date_reg'     => NULL,
                            'lead_key'     => NULL,
                            'account'      => NULL,
                            'user'         => NULL,
                            'group'        => NULL,
                            'username'     => NULL,
                            'password'     => NULL,
                            'email'        => NULL,
                            'name'         => NULL,
                            'locale'       => NULL,
                            'company'      => NULL,
                            'address'      => NULL,
                            'post_code'    => NULL,
                            'country'      => NULL,
                            'region'       => NULL,
                            'city'         => NULL,
                            'alt_city'     => NULL,
                            'phone'        => NULL,
                            'send_emails'  => NULL,
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
     * Get lead from his id
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
     * Get lead from his email
     *
     */
    public function getRegbyEmail( $email )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Email ==========> ('.$email.')'.PHP_EOL; fwrite($this->myfile, $txt);
        if ( !$email ) return false;

        $filter = array( 'email' => $email );
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
    public function setDateReg( $date_reg ) { $this->reg['date_reg'] = $date_reg;  }
    public function setLeadKey( $lead_key ) { $this->reg['lead_key'] = $lead_key;  }
    public function setAccount( $account ) { $this->reg['account'] = $account;  }
    public function setUser( $user ) { $this->reg['user'] = $user;  }
    public function setGroup( $group ) { $this->reg['group'] = $group;  }
    public function setUserName( $username ) { $this->reg['username'] = $username;  }
    public function setPassword( $pasword ) { $this->reg['password'] = $pasword;  }
    public function setEmail( $email ) { $this->reg['email'] = $email;  }
    public function setName( $name ) { $this->reg['name'] = $name;  }
    public function setLocale( $locale ) { $this->reg['locale'] = $locale;  }
    public function setCompany( $company ) { $this->reg['company'] = $company;  }
    public function setAddress( $address ) { $this->reg['address'] = $address;  }
    public function setPostCode( $post_code ) { $this->reg['post_code'] = $post_code;  }
    public function setCountry( $country ) { $this->reg['country'] = $country;  }
    public function setRegion( $region ) { $this->reg['region'] = $region;  }
    public function setCity( $city ) { $this->reg['city'] = $city;  }
    public function setAltCity( $alt_city ) { $this->reg['alt_city'] = $alt_city;  }
    public function setPhone( $phone ) { $this->reg['phone'] = $phone;  }
    public function setSendEmails( $send_emails ) { $this->reg['send_emails'] = $send_emails;  }
    public function setActive( $active ) { $this->reg['active'] = $active;  }

    public function getReg() { return $this->reg; }
    public function getAll( $filter='', $extra='' ) { return $this->db->fetchAll( $this->table, '*', $filter, $extra ); }
    public function getId() { return $this->reg['id']; }
    public function getDateReg() { return $this->reg['date_reg']; }
    public function getLeadKey() { return $this->reg['lead_key']; }
    public function getAccount() { return $this->reg['account']; }
    public function getUser() { return $this->reg['user']; }
    public function getGroup() { return $this->reg['group']; }
    public function getUsername() { return $this->reg['username']; }
    public function getPassword() { return $this->reg['password']; }
    public function getEmail() { return $this->reg['email']; }
    public function getName() { return $this->reg['name']; }
    public function getLocale() { return $this->reg['locale']; }
    public function getCompany() { return $this->reg['company']; }
    public function getAddress() { return $this->reg['address']; }
    public function getPostCode() { return $this->reg['post_code']; }
    public function getCountry() { return $this->reg['country']; }
    public function getRegion() { return $this->reg['region']; }
    public function getCity() { return $this->reg['city']; }
    public function getAltCity() { return $this->reg['alt_city']; }
    public function getPhone() { return $this->reg['phone']; }
    public function getSendEmails() { return $this->reg['send_emails']; }
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
        $this->reg['active'] = ( empty($this->reg['active']) )? '0' : $this->reg['active'];
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }
}