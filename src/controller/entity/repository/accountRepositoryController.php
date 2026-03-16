<?php

namespace src\controller\entity\repository;

use src\controller\entity\accountController;
use src\controller\entity\userController;

use DateTime;
use DateTimeZone;

/**
 * Trait account
 * @package entity
 */
trait accountRepositoryController
{
    /**
     *
     * Create an account
     */
    public function createAccount (
        $name,
        $email,
        $locale,
        $company,
        $address,
        $post_code,
        $country,
        $region,
        $city,
        $alt_city,
        $phone,
        $vat,
        $group,
        $show_to_staff
    )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $this->setNotificationsEmail( $email );
        $this->setLocale( $locale );
        $this->setGroup( $group );
        $this->setName( $name );
        $this->setCompany( $company );
        $this->setAddress( $address );
        $this->setPostCode( $post_code );
        $this->setCountry( $country );
        $this->setRegion( $region );
        $this->setCity( $city );
        $this->setAltCity( $alt_city );
        $this->setPhone( $phone );
        $this->setVat( $vat );
        $this->setShowToStaff( $show_to_staff );
        $this->setAllowStaffUseCard( '1' );
        $this->setActive( ( $this->session->config['verify_account'] )? '0' : '1');
        $this->persist();

        $this->setAccountKey( md5( $this->getId() ) );
        $this->persist();
//$txt = 'Account ========>' . PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->getReg(), TRUE));
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }
}
