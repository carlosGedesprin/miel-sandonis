<?php
//$myfile_groups = fopen(APP_ROOT_PATH.'/var/logs/viewSelects_user_roles.txt', 'w') or die('Unable to open file!');
//$txt = '====================== viewSelects_groups start ==============================================================='.PHP_EOL; fwrite($myfile_groups, $txt);

if ( $reg->getRole() == '' )
{
  $data[$data_options_field] .= '<option value="">'.$this->lang['USER_ROLE_SELECT'].'</option>';
  $data[$data_options_field] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
}
else
{
  $data[$data_options_field] .= '<option value="">'.$this->lang['USER_ROLE_UNSELECT'].'</option>';
  $data[$data_options_field] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
}
foreach ( $rows as $row )
{
    $data[$data_options_field] .= '<option value="'.$row['id'].'"'.(($reg->getRole() == $row['id'])? ' selected="selected" ' : '').'>'.$this->lang[$row['name_lang_key']].'</option>';
}
//$txt = '====================== viewSelects_groups end ==============================================================='.PHP_EOL; fwrite($myfile_groups, $txt);
