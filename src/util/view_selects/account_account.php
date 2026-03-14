<?php
//$myfile_account_account = fopen(APP_ROOT_PATH.'/var/logs/viewSelects_account_account.txt', 'w') or die('Unable to open file!');
//$txt = 'viewSelects_account_account start ==============================================================='.PHP_EOL; fwrite($myfile_account_account, $txt);

if ( $reg->getAccount() == '')
{
    $data['account_options'] .= '<option value="" selected="selected">'.$this->lang['ACCOUNT_SELECT'].'</option>';
    $data['account_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
}
$rows = $this->db->fetchAll('account', 'id, name, group, active', false, 'ORDER BY name');
foreach ( $rows as $row )
{
    $data['account_options'] .= '<option value="'.$row['id'].'"'.(($reg->getAccount() == $row['id'])? ' selected="selected" ' : '').'>';
    $data['account_options'] .= $row['name'];
    switch ($row['group']){
        case '1':
            $data['account_options'] .= ' - '.$this->lang['USER_SUPERADMIN'];
            break;
        case '2':
            $data['account_options'] .= ' - '.$this->lang['USER_ADMIN'];
            break;
        case '3':
            $data['account_options'] .= ' - '.$this->lang['ACCOUNT_STAFF'];
            break;
        case '4':
            $data['account_options'] .= ' - '.$this->lang['ACCOUNT_CUSTOMER'];
    }
    $data['account_options'] .= ( ($row['active'] == '0' )? ' ('.$this->lang['ACCOUNT_NOT_ACTIVE'].')' : '' );
    $data['account_options'] .= ( ($row['active'] == '0' )? ' ('.$this->lang['ACCOUNT_NOT_ACTIVE'].')' : '' );
    $data['account_options'] .= '</option>';
}