<?php

namespace src\controller\entity\repository;

use DateTime;
use DateTimeZone;

/**
 * Trait lead
 * @package entity
 */
trait leadRepositoryController
{
    /**
     *
     * Create a lead
     */
    public function createLead (
        $username,
        $password,
        $email,
        $name,
        $locale,
        $company,
        $address,
        $post_code,
        $country,
        $region,
        $city,
        $alt_city,
        $phone,
        $group
    )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']) );

        $this->setDateReg( $now );
        $this->setUserName( $username );
        $this->setPassword( $password );
        $this->setEmail( $email );
        $this->setName( $name );
        $this->setLocale( $locale );
        $this->setCompany( $company );
        $this->setAddress( $address );
        $this->setPostCode( $post_code );
        $this->setCountry( $country );
        $this->setRegion( $region );
        $this->setCity( $city );
        $this->setAltCity( $alt_city );
        $this->setPhone( $phone );
        $this->setGroup( $group );
        $this->setActive( '1');
        $this->persist();

        $this->setLeadKey( md5( $this->getId() ) );
        $this->persist();
//$txt = 'Lead ========>' . PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->getReg(), TRUE));
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }

}
