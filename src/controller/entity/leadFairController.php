<?php

namespace src\controller\entity;

use DateTime;
use DateTimeZone;
use \src\controller\baseController;

class leadFairController extends baseController
{
    use repository\leadFairRepositoryController;
    use verifications\leadFairVerificationController;

    private $table = 'lead_fair';
    private $reg = array(
                            'id'           => '',
                            'lead_key'     => NULL,
                            'date_reg'     => NULL,
                            'group'        => NULL,
                            'name'         => NULL,
                            'email'        => NULL,
                            'phone'        => NULL,
                            'phone_mobile' => NULL,
                            'locale'       => NULL,
                            'company'      => NULL,
                            'position'     => NULL,
                            'linkedin'     => NULL,
                            'instagram'    => NULL,
                            'twitter'      => NULL,
                            'market'       => NULL,
                            'origin'       => NULL,
                            'address'      => NULL,
                            'post_code'    => NULL,
                            'country'      => NULL,
                            'region'       => NULL,
                            'city'         => NULL,
                            'alt_city'     => NULL,
                            'send_emails'  => NULL,
                            'notes'        => NULL,
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
    public function setLeadKey( $lead_key ) { $this->reg['lead_key'] = $lead_key;  }
    public function setDateReg( $date_reg ) { $this->reg['date_reg'] = $date_reg;  }
    public function setGroup( $group ) { $this->reg['group'] = $group;  }
    public function setName( $name ) { $this->reg['name'] = $name;  }
    public function setEmail( $email ) { $this->reg['email'] = $email;  }
    public function setPhone( $phone ) { $this->reg['phone'] = $phone;  }
    public function setPhoneMobile( $phone_mobile ) { $this->reg['phone_mobile'] = $phone_mobile;  }
    public function setLocale( $locale ) { $this->reg['locale'] = $locale;  }
    public function setCompany( $company ) { $this->reg['company'] = $company;  }
    public function setPosition( $position ) { $this->reg['position'] = $position;  }
    public function setLinkedin( $linkedin ) { $this->reg['linkedin'] = $linkedin;  }
    public function setInstagram( $instagram ) { $this->reg['instagram'] = $instagram;  }
    public function setTwitter( $twitter ) { $this->reg['twitter'] = $twitter;  }
    public function setMarket( $market ) { $this->reg['market'] = $market;  }
    public function setOrigin( $origin ) { $this->reg['origin'] = $origin;  }
    public function setAddress( $address ) { $this->reg['address'] = $address;  }
    public function setPostCode( $post_code ) { $this->reg['post_code'] = $post_code;  }
    public function setCountry( $country ) { $this->reg['country'] = $country;  }
    public function setRegion( $region ) { $this->reg['region'] = $region;  }
    public function setCity( $city ) { $this->reg['city'] = $city;  }
    public function setAltCity( $alt_city ) { $this->reg['alt_city'] = $alt_city;  }
    public function setSendEmails( $send_emails ) { $this->reg['send_emails'] = $send_emails;  }
    public function setNotes( $notes ) { $this->reg['notes'] = $notes;  }
    public function setActive( $active ) { $this->reg['active'] = $active;  }

    public function getReg() { return $this->reg; }
    public function getAll( $filter='', $extra='' ) { return $this->db->fetchAll( $this->table, '*', $filter, $extra ); }
    public function getId() { return $this->reg['id']; }
    public function getLeadKey() { return $this->reg['lead_key']; }
    public function getDateReg() { return $this->reg['date_reg']; }
    public function getGroup() { return $this->reg['group']; }
    public function getName() { return $this->reg['name']; }
    public function getEmail() { return $this->reg['email']; }
    public function getPhone() { return $this->reg['phone']; }
    public function getPhoneMobile() { return $this->reg['phone_mobile']; }
    public function getLocale() { return $this->reg['locale']; }
    public function getCompany() { return $this->reg['company']; }
    public function getPosition() { return $this->reg['position']; }
    public function getLinkedin() { return $this->reg['linkedin']; }
    public function getInstagram() { return $this->reg['instagram']; }
    public function getTwitter() { return $this->reg['twitter']; }
    public function getMarket() { return $this->reg['market']; }
    public function getOrigin() { return $this->reg['origin']; }
    public function getAddress() { return $this->reg['address']; }
    public function getPostCode() { return $this->reg['post_code']; }
    public function getCountry() { return $this->reg['country']; }
    public function getRegion() { return $this->reg['region']; }
    public function getCity() { return $this->reg['city']; }
    public function getAltCity() { return $this->reg['alt_city']; }
    public function getSendEmails() { return $this->reg['send_emails']; }
    public function getNotes() { return $this->reg['notes']; }
    public function getActive() { return $this->reg['active']; }

    /**
     *
     * Change special fields after load from database
     */
    private function loadSpecialFields()
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $this->reg['date_reg'] = ( empty($this->reg['date_reg']) )? NULL : DateTime::createFromFormat('Y-m-d H:i:s', $this->reg['date_reg'], new DateTimeZone($this->session->config['time_zone']));
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     *
     * Change special fields before save to database
     */
    private function setSpecialFields()
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $this->reg['date_reg'] = ( empty($this->reg['date_reg']) )? NULL : $this->reg['date_reg']->format('Y-m-d H:i:s');
        $this->reg['active'] = ( empty($this->reg['active']) )? '0' : $this->reg['active'];
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }
}