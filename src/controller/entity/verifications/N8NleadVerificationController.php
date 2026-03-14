<?php

namespace src\controller\entity\verifications;

use DateTime;
use DateTimeZone;

/**
 * Trait Lead n8n verifications
 * @package entity
 */
trait N8NleadVerificationController
{
    /*
     *
     * Check if another lead n8n has the same name
     *
     * $name : name to search
     * $key : '', 'id' or 'lead_key' key to avoid the self record
     * $lead_id : id or lead_key referred on the $key
     *
     */
    public function leadsWithSameName( $name, $key, $lead_id=NULL, $type=NULL, $type_id=NULL )
    {
//$txt = __METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = $name.' '.$key.' '.$lead_id.' '.$type.' '.$type_id.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
        $filters = array('name' => $name);
        if ( $type ){
            switch ( $type ) {
                case GROUP_AGENT:
                    $filters['agent'] = $type_id;
                    break;
                case GROUP_INTEGRATOR:
                    $filters['integrator'] = $type_id;
                    break;
            }
        }
//$txt = 'Filters ==> '.print_r($filters, true).PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
        switch ( $key ) {
            case '':
                $avoid_self_record = '';
                break;
            case 'id':
                $avoid_self_record = ( $lead_id != NULL && $lead_id != '0')? ' AND `id` <> \''.$lead_id.'\'' : '';
                break;
            case 'lead_key':
                $avoid_self_record = ( $lead_id != NULL && $lead_id != '0')? ' AND `lead_key` <> \''.$lead_id.'\'' : '';
                break;
        }

        $rows = $this->db->fetchAll( $this->table, 'id', $filters, $avoid_self_record );
//$txt = 'Found ==> '.sizeof($rows).PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);

//$txt = __METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return ( sizeof( $rows ) > 0 )? true : false;
    }

    /*
     * Validation
     *
     * Check if another lead has the same notifications email
     *
     * $email : email to search
     * $key : '', 'id' or 'lead_key' key to avoid the self record
     * $lead_id : id or lead_key referred on the $key
     *
     */
    public function leadsWithSameEmail( $email, $key, $lead_id=NULL )
    {
//$txt = __METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Email ==> ('.$email.') Key ==> '.$key.' lead_id ==> '.$lead_id.PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
        switch ($key) {
            case '':
                $rows = $this->db->fetchAll( $this->table, 'id', ['notifications_email' => $email] );
                break;
            case 'id':
                $rows = $this->db->fetchAll( $this->table, 'id', ['notifications_email' => $email], ' AND `id` <> \''.$lead_id.'\'' );
                break;
            case 'lead_key':
                $rows = $this->db->fetchAll( $this->table, 'id', ['notifications_email' => $email], ' AND `lead_key` <> \''.$lead_id.'\'' );
                break;
        }
//$txt = 'Found ==> '.sizeof($rows).PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);

//$txt = __METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return ( sizeof( $rows ) > 0 )? true : false;
    }
}
