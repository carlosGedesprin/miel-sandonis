<?php

namespace src\controller\entity;

use src\controller\baseController;

use DateTime;
use DateTimeZone;

class N8NleadController extends baseController
{
    use repository\N8NleadRepositoryController;
    use verifications\N8NleadVerificationController;

    private $table = 'n8n_lead';
    private $reg = array(
                            'id'           => '',
                            'market'       => NULL,
                            'lead_key'     => NULL,
                            'origin'       => NULL,
                            'date_reg'     => NULL,
                            'name'         => NULL,
                            'company'      => NULL,
                            'position'     => NULL,
                            'locale'       => NULL,
                            'linkedin'     => NULL,
                            'instagram'    => NULL,
                            'twitter'      => NULL,
                            'email'        => NULL,
                            'domain_name'  => NULL,
                            'phone'        => NULL,
                            'phone_mobile' => NULL,
                            'conscience'   => NULL,
                            'bulk_info'    => NULL,
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

        $filter = array( 'id' => $id );
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $this->getRegFromDB( $filter );
    }

    /**
     *
     * Get lead from his token
     *
     */
    public function getRegbyLeadKey( $key )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Lead key ==========> ('.$key.')'.PHP_EOL; fwrite($this->myfile, $txt);
        if ( !$key ) return false;

        $filter = array( 'token' => $key );
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

    public function setReg( $reg ) { $this->reg = array_merge( $this->reg, $reg ); }
    public function setId( $id ) { $this->reg['id'] = $id; }
    public function setMarket( $market ) { $this->reg['market'] = $market; }
    public function setOrigin( $origin ) { $this->reg['origin'] = $origin; }
    public function setLeadKey( $lead_key ) { $this->reg['lead_key'] = $lead_key; }
    public function setDateReg( $date_reg ) { $this->reg['date_reg'] = $date_reg; }
    public function setName( $name ) { $this->reg['name'] = $name; }
    public function setCompany( $company ) { $this->reg['company'] = $company; }
    public function setPosition( $position ) { $this->reg['position'] = $position; }
    public function setLocale( $locale ) { $this->reg['locale'] = $locale; }
    public function setLinkedIn( $linkedin ) { $this->reg['linkedin'] = $linkedin; }
    public function setInstagram( $instagram ) { $this->reg['instagram'] = $instagram; }
    public function setTwitter( $twitter ) { $this->reg['twitter'] = $twitter; }
    public function setEmail( $email ) { $this->reg['email'] = $email; }
    public function setDomainName( $domain_name ) { $this->reg['domain_name'] = $domain_name; }
    public function setPhone( $phone ) { $this->reg['phone'] = $phone; }
    public function setPhoneMobile( $phone_mobile ) { $this->reg['phone_mobile'] = $phone_mobile; }
    public function setConscience( $conscience ) { $this->reg['conscience'] = $conscience; }
    public function setBulkInfo( $bulk_info ) { $this->reg['bulk_info'] = $bulk_info; }
    public function setActive( $active ) { $this->reg['active'] = $active; }

    public function getReg() { return $this->reg; }
    public function getAll( $filter='', $extra='' ) { return $this->db->fetchAll( $this->table, '*', $filter, $extra ); }
    public function getId() { return $this->reg['id']; }
    public function getMarket() { return $this->reg['market']; }
    public function getOrigin() { return $this->reg['origin']; }
    public function getLeadKey() { return $this->reg['lead_key']; }
    public function getDateReg() { return $this->reg['date_reg']; }
    public function getName() { return $this->reg['name']; }
    public function getCompany() { return $this->reg['company']; }
    public function getPosition() { return $this->reg['position']; }
    public function getLocale() { return $this->reg['locale']; }
    public function getLinkedIn() { return $this->reg['linkedin']; }
    public function getInstagram() { return $this->reg['instagram']; }
    public function getTwitter() { return $this->reg['twitter']; }
    public function getEmail() { return $this->reg['email']; }
    public function getDomainName() { return $this->reg['domain_name']; }
    public function getPhone() { return $this->reg['phone']; }
    public function getPhoneMobile() { return $this->reg['phone_mobile']; }
    public function getConscience() { return $this->reg['conscience']; }
    public function getBulkInfo() { return $this->reg['bulk_info']; }
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
        $this->reg['market'] = ( empty($this->reg['market']) )? NULL : $this->reg['market'];
        $this->reg['origin'] = ( empty($this->reg['origin']) )? NULL : $this->reg['origin'];
        $this->reg['date_reg'] = ( empty($this->reg['date_reg']) )? NULL : $this->reg['date_reg']->format('Y-m-d H:i:s');
        $this->reg['name'] = ( empty($this->reg['name']) )? NULL : $this->reg['name'];
        $this->reg['company'] = ( empty($this->reg['company']) )? NULL : $this->reg['company'];
        $this->reg['position'] = ( empty($this->reg['position']) )? NULL : $this->reg['position'];
        $this->reg['locale'] = ( empty($this->reg['locale']) )? NULL : $this->reg['locale'];
        $this->reg['linkedin'] = ( empty($this->reg['linkedin']) )? NULL : $this->reg['linkedin'];
        $this->reg['instagram'] = ( empty($this->reg['instagram']) )? NULL : $this->reg['instagram'];
        $this->reg['twitter'] = ( empty($this->reg['twitter']) )? NULL : $this->reg['twitter'];
        $this->reg['email'] = ( empty($this->reg['email']) )? NULL : strtolower( $this->reg['email'] );
        $this->reg['domain_name'] = ( empty($this->reg['domain_name']) )? NULL : $this->reg['domain_name'];
        $this->reg['phone'] = ( empty($this->reg['phone']) )? NULL : $this->reg['phone'];
        $this->reg['phone_mobile'] = ( empty($this->reg['phone_mobile']) )? NULL : $this->reg['phone_mobile'];
        $this->reg['conscience'] = ( empty($this->reg['conscience']) )? NULL : $this->reg['conscience'];
        $this->reg['bulk_info'] = ( empty($this->reg['bulk_info']) )? NULL : $this->reg['bulk_info'];
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }
}