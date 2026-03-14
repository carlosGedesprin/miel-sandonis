<?php

namespace src\controller\entity\repository;


/**
 * Trait Credential Type Data
 * @package entity
 */
trait credentialTypeDataRepositoryController
{/**
 *
 * Delete Data from a credential type
 *
 * @return void
 */
    public function deleteCredentiaTypeData( $credential_type_id )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $filter_select = array(
                                'credential_type' => $credential_type_id,
        );
        $extra_select = '';
        $rows = $this->getAll( $filter_select, $extra_select);
        foreach ( $rows as $row )
        {
            $this->getRegbyId( $row['id'] );
            $this->deleteORL();
        }
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }

}
