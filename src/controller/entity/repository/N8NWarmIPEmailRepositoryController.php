<?php

namespace src\controller\entity\repository;


/**
 * Trait n8n warm IP email
 * @package entity
 */
trait N8NWarmIPEmailRepositoryController
{
    /**
     *
     * Create a n8n Warm IP account email
     */
    public function createEmail (
        $account,
        $date_sent,
        $subject,
        $body
    )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $this->setAccount( $account );
        $this->setDateSent( $date_sent );
        $this->setSubject( $subject );
        $this->setBody( $body );

        $this->persist();
//$txt = 'N8N Warm IP email ========>' . PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->getReg(), TRUE));
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }
    /**
     *
     * Get emails of an account
     */
    public function getAccountEmails ( $account )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $filter_select = array(
                                'account' => $account
        );
        $extra_select = ' ORDER BY `date_sent` DESC';

        $account_emails = $this->getAll( $filter_select, $extra_select );
//$txt = 'Account '.$account.' emails ========>' . PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($account_emails, TRUE));
        return $account_emails;
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }
    /**
     *
     * Get emails of an account and warm email
     */
    public function getAccountEmailsByEmailToWarm ( $account, $mail_to_warm )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $filter_select = array(
                                'account' => $account,
                                'warm_email' => $mail_to_warm,
        );
        $extra_select = ' ORDER BY `date_sent` DESC';

        $account_emails = $this->getAll( $filter_select, $extra_select );
//$txt = 'Account '.$account.' emails ========>' . PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($account_emails, TRUE));
        return $account_emails;
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }
}
