<?php
//$myfile_product_all = fopen(APP_ROOT_PATH.'/var/logs/viewSelects_product_all.txt', 'w') or die('Unable to open file!');
//$txt = 'viewSelects_product_all start ==============================================================='.PHP_EOL; fwrite($myfile_product_all, $txt);

if ( $reg->getProduct() == '')
{
  $data['plan_options'] .= '<option value="" selected="selected">'.$this->lang['PLAN_SELECT'].'</option>';
  $data['plan_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
}
$rows = $this->db->fetchAll('plan', 'id, name, plan_key', false, 'ORDER BY name');
foreach ( $rows as $row)
{
    $data['plan_options'] .= '<option value="'.$row['plan_key'].'"'.(($reg->getProduct() == $row['plan_key'])? ' selected="selected" ' : '').'>'.$row['name'].'</option>';
}