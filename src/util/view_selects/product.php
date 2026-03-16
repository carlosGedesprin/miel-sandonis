<?php
//$myfile_product_all = fopen(APP_ROOT_PATH.'/var/logs/viewSelects_product_all.txt', 'w') or die('Unable to open file!');
//$txt = 'viewSelects_product_all start ==============================================================='.PHP_EOL; fwrite($myfile_product_all, $txt);

if ( $reg->getProduct() == '')
{
  $data['product_options'] .= '<option value="" selected="selected">'.$this->lang['PRODUCT_SELECT'].'</option>';
  $data['product_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
}
$rows = $this->db->fetchAll('product', 'id, name', false, 'ORDER BY name');
foreach ( $rows as $row)
{
    $data['product_options'] .= '<option value="'.$row['id'].'"'.(($reg->getProduct() == $row['id'])? ' selected="selected" ' : '').'>'.$row['name'].'</option>';
}