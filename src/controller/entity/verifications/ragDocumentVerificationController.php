<?php

namespace src\controller\entity\verifications;

use DateTime;
use DateTimeZone;

/**
 * Trait rag document verifications
 * @package entity
 */
trait ragDocumentVerificationController
{

    /*
     *
     * Check if another document has the same name
     *
     * $name : name to search
     * $type_key : '', 'id' or 'key' key to avoid the self record
     * $rag_document_id : id or rag_document_key referred on the $type_key
     * $rag: Name not duplicated per rag, expected rag id
     */
    public function ragDocumentWithSameName( $name, $type_key, $key, $rag )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Name ('.$name.') Type key ('.$type_key.') Key ('.$key.') Rag ('.$rag.')'.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
        $filters = array(
                        'name' => $name,
                        'rag' => $rag
        );
//$txt = 'Filters ==> '.print_r($filters, true).PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
        $extra = ( $key == 0 )? '' : ' AND '.( ( $type_key == 'id' )? 'id' : 'rag_key').' <> \''.$key.'\'';
//$txt = 'Extra ==> '.$extra.PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);

        $rows = $this->getAll( $filters, $extra );
//$txt = 'Found ==> '.sizeof($rows).PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return ( sizeof( $rows ) > 0 )? true : false;
    }
}
