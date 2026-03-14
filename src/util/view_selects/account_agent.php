<?php
//$myfile_account_agent = fopen(APP_ROOT_PATH.'/var/logs/viewSelects_account_agent.txt', 'w') or die('Unable to open file!');
//$txt = 'viewSelects_account_agent start ==============================================================='.PHP_EOL; fwrite($myfile_account_agent, $txt);

if ( $reg->getAgent() == '')
{
    $data['agent_options'] .= '<option value="" selected="selected">'.$this->lang['ACCOUNT_AGENT_SELECT'].'</option>';
    $data['agent_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
}
$rows = $this->db->fetchAll('account', 'id, name, active', ['group' => '5'], 'ORDER BY name');
foreach ( $rows as $row )
{
    $data['agent_options'] .= '<option value="'.$row['id'].'"'.(($reg->getAgent() == $row['id'])? ' selected="selected" ' : '').'>';
    $data['agent_options'] .= $row['id'].' - '.$row['name'].( ($row['active'] == '0' )? ' ('.$this->lang['ACCOUNT_AGENT_NOT_ACTIVE'].')' : '' );
    $data['agent_options'] .= '</option>';
}