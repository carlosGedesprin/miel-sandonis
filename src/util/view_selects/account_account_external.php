<?php
//$myfile_account_external = fopen(APP_ROOT_PATH.'/var/logs/viewSelects_account_external.txt', 'w') or die('Unable to open file!');
//$txt = 'viewSelects_account_external start ==============================================================='.PHP_EOL; fwrite($myfile_account_external, $txt);

if ( $reg->getAccount() == '')
{
    $data['account_options'] .= '<option value="" selected="selected">'.$this->lang['CUSTOMER_SELECT'].'</option>';
    $data['account_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
}
$rows = $this->db->fetchAll('account', 'id, name, active', false, ' group in ("4", "5", "6") ORDER BY name');
foreach ( $rows as $row )
{
    $data['account_options'] .= '<option value="'.$row['id'].'"'.(($reg->getAccount() == $row['id'])? ' selected="selected" ' : '').'>';
    $data['account_options'] .= $row['name'];
    switch ($row['group']){
        case '4':
            $data['account_options'] .= ' - '.$this->lang['USER_CUSTOMER'];
            break;
        case '5':
            $data['account_options'] .= ' - '.$this->lang['USER_AGENT'];
            break;
        case '6':
            $data['account_options'] .= ' - '.$this->lang['USER_INTEGRATOR'];
            break;
    }
    $data['account_options'] .= ( ($row['active'] == '0' )? ' ('.$this->lang['ACCOUNT_CUSTOMER_NOT_ACTIVE'].')' : '' );
    $data['account_options'] .= ( ($row['active'] == '0' )? ' ('.$this->lang['ACCOUNT_CUSTOMER_NOT_ACTIVE'].')' : '' );
    $data['account_options'] .= '</option>';
}