<?php
//$myfile_user_new_account = fopen(APP_ROOT_PATH.'/var/logs/viewSelects_user_new_account.txt', 'w') or die('Unable to open file!');
//$txt = 'viewSelects_user_new_account start ==============================================================='.PHP_EOL; fwrite($myfile_user_new_account, $txt);

if ( $reg->getAccount() == '')
{
    $data['account_options'] .= '<option value="" selected="selected">'.$this->lang['ACCOUNT_SELECT'].'</option>';
    $data['account_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
}
$data['account_options'] .= '<option value="0">'.$this->lang['ACCOUNT_NEW'].'</option>';
$data['account_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
$rows = $this->db->fetchAll('account', 'id, group, name, active', ['show_to_staff' => '1'], 'ORDER BY name');
foreach ( $rows as $row )
{
    $data['account_options'] .= '<option value="'.$row['id'].'"'.(($reg->getAccount() == $row['id'])? ' selected="selected" ' : '').'>';
    $data['account_options'] .= $row['name'];
    switch ($row['group']){
        case '4':
            $data['account_options'] .= ' - '.$this->lang['ACCOUNT_CUSTOMER'];
            break;
        case '5':
            $data['account_options'] .= ' - '.$this->lang['ACCOUNT_AGENT'];
            break;
        case '6':
            $data['account_options'] .= ' - '.$this->lang['ACCOUNT_INTEGRATOR'];
            break;
    }
    $data['account_options'] .= ( ($row['active'] == '0' )? ' ('.$this->lang['ACCOUNT_NOT_ACTIVE'].')' : '' );
    $data['account_options'] .= '</option>';
}