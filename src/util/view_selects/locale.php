<?php
//$myfile_locale = fopen(APP_ROOT_PATH.'/var/logs/viewSelects_locale.txt', 'w') or die('Unable to open file!');
//$txt = 'viewSelects_locale start ==============================================================='.PHP_EOL; fwrite($myfile_locale, $txt);

if ( $reg->getLocale() == '' )
{
  $data['locale_options'] .= '<option value="" selected="selected">'.$this->lang['LANG_SELECT'].'</option>';
  $data['locale_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
}
$rows = $this->db->fetchAll('lang', 'code_2a, iso_name', ['active' => '1'], 'ORDER BY iso_name');
foreach ( $rows as $row )
{
    $data['locale_options'] .= '<option value="'.$row['code_2a'].'"'.(($reg->getLocale() == $row['code_2a'])? ' selected="selected" ' : '').'>'.$row['iso_name'].'</option>';
}