<?php
//$myfile_user_account_group = fopen(APP_ROOT_PATH.'/var/logs/viewSelects_user_account_group.txt', 'w') or die('Unable to open file!');
//$txt = 'viewSelects_user_account_group start ==============================================================='.PHP_EOL; fwrite($myfile_user_account_group, $txt);

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