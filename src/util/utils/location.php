<?php
namespace src\util\utils;

/**
 * Trait location
 * @package Utils
 */
trait location
{
    /**
     * Get the country name
     */
    public function getCountryName( $id )
    {
        $row = $this->db->fetchOne('loc_countries', 'name', ['id' => $id]);
        $name = $row['name'];
        unset($row);
        return $name;
    }

    /**
     * Get the region name
     */
    public function getRegionName( $id )
    {
        $row = $this->db->fetchOne('loc_regions', 'name', ['id' => $id]);
        $name = $row['name'];
        unset($row);
        return $name;
    }

    /**
     * Get the company address in one line
     *
     * Used on emails footer
     *
     * @return string    Company address
     */
    public function getCompanyAddressOneLine( )
    {
        $address = $this->db->fetchfield('config', 'config_value', ['config_name' => 'company_address_1']);
        $address .= $this->db->fetchfield('config', 'config_value', ['config_name' => 'company_address_2']);
        $address .= $this->db->fetchfield('config', 'config_value', ['config_name' => 'company_address_3']);
        $address .= $this->db->fetchfield('config', 'config_value', ['config_name' => 'company_address_4']);
        $address .= $_ENV['company_post_code'].' ';
        $address .= $this->db->fetchfield('config', 'config_value', ['config_name' => 'company_address_5']);
        return $address;

    }

    /**
     * Get full address
     *
     * @param string $user     Address, Country id, Region id, City id, alt city, Post Code, Format (one_line, pile)
     * @return string   Address in one line
     */
    public function getFullAddress( $address, $country, $region, $city, $altcity, $post_code='', $format='one_line' )
    {
        $row = $this->db->fetchOne('loc_countries', 'name', ['id' => $country]);
        $country = $row['name'];
        unset($row);
        $row = $this->db->fetchOne('loc_regions', 'name', ['id' => $region]);
        $region = $row['name'];
        unset($row);
        if ( $city != '0')
        {
            $row = $this->db->fetchOne('loc_cities', 'name', ['id' => $city]);
            $city = $row['name'];
            unset($row);
        }else{
            $city = $altcity;
        }
        $separator = ( $format = 'one_line' )? ' ' : '<br />';

        $address = $address . $separator . $city;
        if ( $post_code != '' ) $address .= $separator . $post_code;
        $address .=  $separator . $region . $separator . $country;

        return $address;
    }

    /**
     * Get the Name and address for letters
     *
     * @param $reg      Array with address details
     * @return string   Formated address string
     */
    public function getLetterSendTo( $reg )
    {
        $string = $reg['name'].PHP_EOL;
        if ( !empty($reg['address_1']) && !empty($reg['address_2']) ) $string .= $reg['address_1'].', '.$reg['address_2'].PHP_EOL;
        //if ( !empty($reg['address_1']) ) $string .= $reg['address_1'].PHP_EOL;
        //if ( !empty($reg['address_2']) ) $string .= $reg['address_2'].PHP_EOL;
        if ( !empty($reg['address_3']) ) $string .= $reg['address_3'].PHP_EOL;
        if ( !empty($reg['address_4']) && !empty($reg['address_5']) ) $string .= $reg['address_4'].', '.$reg['address_5'].PHP_EOL;
        //if ( !empty($reg['address_5']) ) $string .= $reg['address_5'].PHP_EOL;
        $string .= $reg['postcode'];
        return $string;
    }
}
