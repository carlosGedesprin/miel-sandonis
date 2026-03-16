<?php
//$myfile_account_groups = fopen(APP_ROOT_PATH.'/var/logs/viewSelects_account_groups.txt', 'w') or die('Unable to open file!');
//$txt = 'viewSelects_account_groups start ==============================================================='.PHP_EOL; fwrite($myfile_account_groups, $txt);

if ( $account->getGroup() == '')
{
  $data['group_options'] .= '<option value="" selected="selected">'.$this->lang['GROUP_SELECT'].'</option>';
  $data['group_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
}
$rows = $this->db->fetchAll('group', 'id, name', ['show_to_staff' => '1'], 'ORDER BY name');
foreach ( $rows as $row)
{
    $data['group_options'] .= '<option value="'.$row['id'].'"'.(($account->getGroup() == $row['id'])? ' selected="selected" ' : '').'>'.$row['name'].'</option>';
}