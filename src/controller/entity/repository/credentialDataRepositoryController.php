<?php

namespace src\controller\entity\repository;


/**
 * Trait Credential Data
 * @package entity
 */
trait credentialDataRepositoryController
{
    /**
     *
     * Delete all Data from a credential
     *
     * @return void
     */
    public function deleteCredentialData( $credential_id )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $filter_select = array(
                                'credential' => $credential_id,
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
