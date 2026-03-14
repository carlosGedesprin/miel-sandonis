<?php
//$myfile_active = fopen(APP_ROOT_PATH.'/var/logs/viewSelects_active.txt', 'w') or die('Unable to open file!');
//$txt = 'viewSelects_active start ==============================================================='.PHP_EOL; fwrite($myfile_active, $txt);

if ( $reg->getActive() == '' )
{
    $data['active_options'] .= '<option value="" selected="selected"><b>' . $this->lang['SELECT'] . '</b></option>';
    $data['active_options'] .= '<option disabled>' . str_repeat('&#x2500', 16) . '</option>';
}
$data['active_options'] .= '<option value="0"' . (($reg->getActive() == '0') ? ' selected="selected" ' : '') . '>'.$this->lang['NO'].'</option>';
$data['active_options'] .= '<option value="1"' . (($reg->getActive() == '1') ? ' selected="selected" ' : '') . '>'.$this->lang['YES'].'</option>';