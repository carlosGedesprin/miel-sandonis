<?php

namespace src\controller\entity\repository;


/**
 * Trait userProfile
 * @package entity
 */
trait userProfileRepositoryController
{

    public function createUserProfile( $user_id, $user_name )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $this->setUser( $user_id );
        $this->setName( $user_name );
//$txt = 'Profile ========>' . PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->getReg(), TRUE));
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $this->persist();
    }
}
