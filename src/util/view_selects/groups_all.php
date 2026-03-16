<?php

if ( $reg->getGroup() == '' )
{
  $data['group_options'] .= '<option value="" selected="selected">'.$this->lang['GROUP_SELECT'].'</option>';
  $data['group_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
}
$rows = $this->db->fetchAll('group', 'id, name', false, 'ORDER BY name');
foreach ( $rows as $row )
{
    $data['group_options'] .= '<option value="'.$row['id'].'"'.(( $reg->getGroup() == $row['id'])? ' selected="selected" ' : '').'>'.$row['name'].'</option>';
}