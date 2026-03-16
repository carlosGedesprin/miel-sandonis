<?php

namespace src\controller\entity\repository;


/**
 * Trait lead n8n email
 * @package entity
 */
trait N8NleadEmailRepositoryController
{
    /**
     *
     * Create a lead n8n
     */
    public function createLeadEmail (
        $lead,
        $date_sent,
        $subject,
        $body
    )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $this->setLead( $lead );
        $this->setDateSent( $date_sent );
        $this->setSubject( $subject );
        $this->setBody( $body );

        $this->persist();
//$txt = 'Lead email ========>' . PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->getReg(), TRUE));
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }
    /**
     *
     * Get emails of a lead
     */
    public function getLeadEmails ( $lead )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $filter_select = array(
                                'lead' => $lead
        );
        $extra_select = ' ORDER BY `date_sent` DESC';

        $lead_emails = $this->getAll( $filter_select, $extra_select );
//$txt = 'Lead emails ========>' . PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($lead_emails, TRUE));
        return $lead_emails;
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }
}
