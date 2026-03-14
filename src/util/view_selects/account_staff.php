<?php
//$myfile_account_staff = fopen(APP_ROOT_PATH.'/var/logs/viewSelects_account_staff.txt', 'w') or die('Unable to open file!');
//$txt = 'viewSelects_account_staff start ==============================================================='.PHP_EOL; fwrite($myfile_account_staff, $txt);

if ( $reg->getStaff() == '')
{
    $data['staff_options'] .= '<option value="" selected="selected">'.$this->lang['STAFF_SELECT'].'</option>';
    $data['staff_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
}
$rows = $this->db->fetchAll('account', 'id, name, active', ['group' => '5'], 'ORDER BY name');
foreach ( $rows as $row )
{
    $data['staff_options'] .= '<option value="'.$row['id'].'"'.(($reg->getStaff() == $row['id'])? ' selected="selected" ' : '').'>';
    $data['staff_options'] .= $row['id'].' - '.$row['name'].( ($row['active'] == '0' )? ' ('.$this->lang['ACCOUNT_STAFF_NOT_ACTIVE'].')' : '' );
    $data['staff_options'] .= '</option>';
}