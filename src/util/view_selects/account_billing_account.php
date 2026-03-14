<?php

use src\controller\entity\accountController;

//$myfile_account_billing_account = fopen(APP_ROOT_PATH.'/var/logs/viewSelects_account_billing_account.txt', 'w') or die('Unable to open file!');
//$txt = 'viewSelects_account_billing_account start ==============================================================='.PHP_EOL; fwrite($myfile_account_billing_account, $txt);

if ( $reg->getBillingAccount() == '')
{
    $data['billing_account_options'] .= '<option value="" selected="selected">'.$this->lang['ACCOUNT_SELECT'].'</option>';
    $data['billing_account_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
}
//$rows = $this->db->fetchAll('account', 'id, name, group, active', false, 'ORDER BY name');
$filter_select = '';
$extra_select = 'ORDER BY `name`';
$billing_account = new accountController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
$rows = $billing_account->getAll( $filter_select, $extra_select);
//$txt = 'Accounts =========='.PHP_EOL; fwrite($myfile_account_billing_account, $txt);
//fwrite($myfile_account_billing_account, print_r($rows, TRUE)); $txt = PHP_EOL; fwrite($myfile_account_billing_account, $txt);
foreach ( $rows as $row )
{
    $data['billing_account_options'] .= '<option value="'.$row['id'].'"'.(($reg->getAccount() == $row['id'])? ' selected="selected" ' : '').'>';
    $data['billing_account_options'] .= $row['name'];
    switch ($row['group']){
        case '1':
            $data['billing_account_options'] .= ' - '.$this->lang['USER_SUPERADMIN'];
            break;
        case '2':
            $data['billing_account_options'] .= ' - '.$this->lang['USER_ADMIN'];
            break;
        case '3':
            $data['billing_account_options'] .= ' - '.$this->lang['ACCOUNT_STAFF'];
            break;
        case '4':
            $data['billing_account_options'] .= ' - '.$this->lang['ACCOUNT_CUSTOMER'];
    }
    $data['billing_account_options'] .= ( ($row['active'] == '0' )? ' ('.$this->lang['ACCOUNT_NOT_ACTIVE'].')' : '' );
    $data['billing_account_options'] .= ( ($row['active'] == '0' )? ' ('.$this->lang['ACCOUNT_NOT_ACTIVE'].')' : '' );
    $data['billing_account_options'] .= '</option>';
}
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($myfile_account_billing_account, $txt);
//fclose( $myfile_account_billing_account );