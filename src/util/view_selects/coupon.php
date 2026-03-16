<?php

if ( $reg->getCoupon() == '')
{
    $data['coupon_options'] .= '<option value="" selected="selected">'.$this->lang['COUPON_SELECT'].'</option>';
    $data['coupon_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
}
$rows = $this->db->fetchAll('coupon', 'id, name', ['active' => '1'], 'ORDER BY name');
if ( sizeof( $rows ) )
{
    foreach ( $rows as $row)
    {
        $data['coupon_options'] .= '<option value="'.$row['id'].'"'.(($reg->getCoupon() == $row['id'])? ' selected="selected" ' : '').'>'.$row['name'].'</option>';
    }
}
else
{
    $data['coupon_options'] .= '<option value="" selected="selected">'.$this->lang['COUPON_NOT_FOUND'].'</option>';
}
