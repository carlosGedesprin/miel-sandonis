<?php
//$myfile_groups = fopen(APP_ROOT_PATH.'/var/logs/viewSelects_main_users.txt', 'w') or die('Unable to open file!');
//$txt = 'viewSelects_main_users start ==============================================================='.PHP_EOL; fwrite($myfile_groups, $txt);

if ( $reg->getMainUser() == '' )
{
  $data[$data_options_field] .= '<option value="">'.$this->lang['USER_SELECT'].'</option>';
  $data[$data_options_field] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
}
else
{
  $data[$data_options_field] .= '<option value="">'.$this->lang['USER_UNSELECT'].'</option>';
  $data[$data_options_field] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
}
foreach ( $rows as $row )
{
    $data[$data_options_field] .= '<option value="'.$row['id'].'"'.(($reg->getMainUser() == $row['id'])? ' selected="selected" ' : '').'>'.$row['name'].'</option>';
}