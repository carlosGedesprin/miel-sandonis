<?php

namespace src\controller\entity\verifications;

use DateTime;
use DateTimeZone;

/**
 * Trait group verifications
 * @package entity
 */
trait groupVerificationController
{
    /*
     *
     * Check if another group has the same name
     *
     * $name : name to search
     * $group_id : id to be avoided
     *
     */
    public function groupsWithSameName( $name, $group_id=NULL )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Name '.$name.' Group_id '.$group_id.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
        $avoid_self_record = ( $group_id != NULL && $group_id != '0')? ' AND `id` <> \''.$group_id.'\'' : '';

        $rows = $this->getAll( ['name' => $name], $avoid_self_record );
//$txt = 'Found ==> '.sizeof($rows).PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return ( sizeof( $rows ) > 0 )? true : false;
    }
    /*
     *
     * Check if another group has the same role
     *
     * $role : role to search
     * $group_id : id to be avoided
     *
     */
    public function groupsWithSameRole( $role, $group_id=NULL )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Role '.$role.' Group_id '.$group_id.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
        $avoid_self_record = ( $group_id != NULL && $group_id != '0')? ' AND `id` <> \''.$group_id.'\'' : '';

        $rows = $this->getAll( ['role' => $role], $avoid_self_record );
//$txt = 'Found ==> '.sizeof($rows).PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return ( sizeof( $rows ) > 0 )? true : false;
    }
}
