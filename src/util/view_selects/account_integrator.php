<?php
//$myfile_account_integrator = fopen(APP_ROOT_PATH.'/var/logs/viewSelects_account_integrator.txt', 'w') or die('Unable to open file!');
//$txt = 'viewSelects_account_integrator start ==============================================================='.PHP_EOL; fwrite($myfile_account_integrator, $txt);

if ( $reg->getIntegrator() == '')
{
    $data['integrator_options'] .= '<option value="" selected="selected">'.$this->lang['ACCOUNT_INTEGRATOR_SELECT'].'</option>';
    $data['integrator_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
}
$rows = $this->db->fetchAll('account', 'id, name, active', ['group' => '6'], 'ORDER BY name');
foreach ( $rows as $row )
{
    $data['integrator_options'] .= '<option value="'.$row['id'].'"'.(($reg->getIntegrator() == $row['id'])? ' selected="selected" ' : '').'>';
    $data['integrator_options'] .= $row['id'].' - '.$row['name'].( ($row['active'] == '0' )? ' ('.$this->lang['ACCOUNT_INTEGRATOR_NOT_ACTIVE'].')' : '' );
    $data['integrator_options'] .= '</option>';
}