<?php
//$myfile_city_region_country_all = fopen(APP_ROOT_PATH.'/var/logs/viewSelects_CityRegionCountryAll.txt', 'w') or die('Unable to open file!');
//$txt = 'viewSelects_CityRegionCountryAll start ==============================================================='.PHP_EOL; fwrite($myfile_city_region_country_all, $txt);

    if ( $reg->getCountry() == '' )
    {
        $data['city_options'] .= '<option value="">' . $this->lang['COUNTRY_SELECT'] . '</option>';
    }
    else if ( $reg->getRegion() == '' )
    {
        $data['city_options'] .= '<option value="">' . $this->lang['REGION_SELECT'] . '</option>';
    }
    else
    {
//$txt = 'Country ========== ('.$reg->getCountry().')'.PHP_EOL; fwrite($myfile_city_all, $txt);
//$txt = 'Region ========== ('.$reg->getRegion().')'.PHP_EOL; fwrite($myfile_city_all, $txt);
        $api_data = array(
                            'data' => array(
                                            'api_key' => $this->session->config['locations_api_key'],
                                            'country' => $reg->getCountry(),
                                            'region' => $reg->getRegion(),
                                            'lang' => $this->session->getLanguageCode2a(),
                            )
        );
//$txt = 'Api data =========='.PHP_EOL; fwrite($myfile_city_region_country_all, $txt);
//fwrite($myfile_city_region_country_all, print_r($api_data, TRUE)); $txt = PHP_EOL; fwrite($myfile_city_region_country_all, $txt);
        $route = '/get_cities';
        $api_response = $this->utils->get_from_locations_api( $route, $api_data );
//$txt = 'Api response =========='.PHP_EOL; fwrite($myfile_city_region_country_all, $txt);
//fwrite($myfile_city_region_country_all, print_r($api_response, TRUE)); $txt = PHP_EOL; fwrite($myfile_city_region_country_all, $txt);

        if ( $api_response['status'] == 'OK')
        {
            if ( $reg->getCity() == '' )
            {
                $data['city_options'] .= '<option value=""' . (($reg->getCity() == '') ? ' selected="selected" ' : '') . '>' . $this->lang['CITY_SELECT'] . '</option>';
                $data['city_options'] .= '<option disabled>' . str_repeat('&#x2500', 16) . '</option>';
            }
            if ( $reg->getCity() == '-' )
            {
                $data['city_options'] .= '<option disabled>' . str_repeat('&#x2500', 16) . '</option>';
                $data['city_options'] .= '<option value="' . $reg->getCity() . '" selected="selected">' . $this->lang['CITY_NOT_IN_THE_LIST'] . '</option>';
                $data['city_options'] .= '<option disabled>' . str_repeat('&#x2500', 16) . '</option>';
            }
            foreach ( $api_response['msg'] as $key => $city)
            {
//$txt = 'City after api =========='.PHP_EOL; fwrite($myfile_city_all, $txt);
//fwrite($myfile_city_all, print_r($city, TRUE)); $txt = PHP_EOL.PHP_EOL; fwrite($myfile_city_all, $txt);
                $data['city_options'] .= '<option';
                $data['city_options'] .= ' value="'.$city['city_code'].'"';
                $data['city_options'] .= (($reg->getCity() == $city['city_code'])? ' selected="selected" ' : '');
                $data['city_options'] .= '>'.$city['name'];
                $data['city_options'] .= ( $city['lang_code_2a'] != $this->session->getLanguageCode2a() )? ' ['.$city['lang_code_2a'].']' : '';
                $data['city_options'] .= '</option>';
            }
        }
        else
        {
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error('API Error ');
            $this->logger_err->error('URL ('.$url_to_call.')');
            $this->logger_err->error('Error Msg -> '.$api_response['msg'].')');
            $this->logger_err->error('*************************************************************************');

            $data['city_options'] .= '<option value="" selected="selected">'.$this->lang['CITY_NOT_FOUND'].'</option>';
        }
    }
