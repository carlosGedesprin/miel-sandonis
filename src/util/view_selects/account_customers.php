<?php
//$myfile_account_customers = fopen(APP_ROOT_PATH.'/var/logs/viewSelects_account_customers.txt', 'w') or die('Unable to open file!');
//$txt = 'viewSelects_account_customers start ==============================================================='.PHP_EOL; fwrite($myfile_account_customers, $txt);

if ( $reg->getAccount() == '')
{
    $data['account_options'] .= '<option value="" selected="selected">'.$this->lang['ACCOUNT_CUSTOMER_SELECT'].'</option>';
    $data['account_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
}
$rows = $this->db->fetchAll('account', 'id, name, active', ['group' => '4'], 'ORDER BY name');
foreach ( $rows as $row )
{
    $data['account_options'] .= '<option value="'.$row['id'].'"'.(($reg->getAccount() == $row['id'])? ' selected="selected" ' : '').'>';
    $data['account_options'] .= $row['id'].' - '.$row['name'].( ($row['active'] == '0' )? ' ('.$this->lang['ACCOUNT_CUSTOMER_NOT_ACTIVE'].')' : '' );
    $data['account_options'] .= '</option>';
}