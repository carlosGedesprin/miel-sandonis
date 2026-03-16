<?php

namespace src\controller\entity\repository;

use DateTime;
use DateTimeZone;

/**
 * Trait lead fair
 * @package entity
 */
trait leadFairRepositoryController
{
    /**
     *
     * Create a lead
     */
    public function createLeadFair (
        $group,
        $name,
        $email,
        $phone,
        $locale
    )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']) );

        $this->setDateReg( $now );
        $this->setGroup( $group );
        $this->setName( $name );
        $this->setEmail( $email );
        $this->setPhone( $phone );
        $this->setLocale( $locale );

        $this->setActive( '1');
        $this->persist();

        $this->setLeadKey( md5( $this->getId() ) );
        $this->persist();
//$txt = 'Lead ========>' . PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->getReg(), TRUE));
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }

}
