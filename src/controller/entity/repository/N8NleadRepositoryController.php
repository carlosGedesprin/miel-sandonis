<?php

namespace src\controller\entity\repository;


use DateTime;
use DateTimeZone;

/**
 * Trait lead n8n
 * @package entity
 */
trait N8NleadRepositoryController
{
    /**
     *
     * Create a lead n8n
     */
    public function createLead (
        $name,
        $email,
        $phone=NULL
    )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']));

        $this->setDateReg( $now );
        $this->setName( $name );
        $this->setEmail( $email );
        if ( !empty( $phone ) ) $this->setPhone( $phone );
        $this->persist();

        $this->setLeadKey( md5( $this->getId() ) );
        $this->persist();
//$txt = 'Lead ========>' . PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->getReg(), TRUE));
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }
}
