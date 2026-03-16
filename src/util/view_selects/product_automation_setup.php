<?php

if ( $reg->getProductSetup() == '')
{
  $data['product_setup_options'] .= '<option value="">'.$this->lang['PRODUCT_SELECT'].'</option>';
  $data['product_setup_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
}
else
{
  $data['product_setup_options'] .= '<option value="">'.$this->lang['PRODUCT_UNSELECT'].'</option>';
  $data['product_setup_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
}
$rows = $this->db->fetchAll('product', 'id, name', ['product_type' => PRODUCT_TYPE_AUTOMATION_SETUP, 'active' => '1'], 'ORDER BY `name`');
foreach ( $rows as $row)
{
    $data['product_setup_options'] .= '<option value="'.$row['id'].'"'.(($reg->getProductSetup() == $row['id'])? ' selected="selected" ' : '').'>'.$row['name'].'</option>';
}