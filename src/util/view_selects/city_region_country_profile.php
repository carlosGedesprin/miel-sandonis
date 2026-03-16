<?php
$myfile_city_region_country_profile = fopen(APP_ROOT_PATH.'/var/logs/viewSelects_CityRegionCountryProfile.txt', 'w') or die('Unable to open file!');
$txt = 'viewSelects_CityRegionCountryProfile start ==============================================================='.PHP_EOL; fwrite($myfile_city_region_country_profile, $txt);

    if ( $userProfile->getCountry() == '' )
    {
        $data['city_options'] .= '<option value="">' . $this->lang['COUNTRY_SELECT'] . '</option>';
    }
    else if ( $userProfile->getRegion() == '' )
    {
        $data['city_options'] .= '<option value="">' . $this->lang['REGION_SELECT'] . '</option>';
    }
    else
    {
    //$txt = 'Country ========== ('.$userProfile->getCountry().')'.PHP_EOL; fwrite($myfile_city_region_country_profile, $txt);
    //$txt = 'Region ========== ('.$userProfile->getRegion().')'.PHP_EOL; fwrite($myfile_city_region_country_profile, $txt);

        $api_data = array(
                            'data' => array(
                                            'api_key' => $this->session->config['locations_api_key'],
                                            'lang' => $this->session->getLanguageCode2a(),
                                            'country_code_2a' => $userProfile->getCountry(),
                                            'region_code' => $userProfile->getRegion(),
                            )
        );
        $route = '/get_cities';
        $api_response = $this->utils->get_from_locations_api( $route, $api_data );
//$txt = 'Api response =========='.PHP_EOL; fwrite($myfile_city_region_country_profile, $txt);
//fwrite($myfile_city_region_country_profile, print_r($api_response, TRUE)); $txt = PHP_EOL; fwrite($myfile_city_region_country_profile, $txt);

        if ( !empty ( $api_response ) && $api_response['status'] == 'OK' )
        {
            if ( $userProfile->getCity() == '' )
            {
                $data['city_options'] .= '<option value=""' . (($userProfile->getCity() == '') ? ' selected="selected" ' : '') . '>' . $this->lang['CITY_SELECT'] . '</option>';
                $data['city_options'] .= '<option disabled>' . str_repeat('&#x2500', 16) . '</option>';
            }
            foreach ( $api_response['msg'] as $key => $city )
            {
                $data['city_options'] .= '<option';
                $data['city_options'] .= ' value="'.$city['city_code'].'"';
                $data['city_options'] .= (($userProfile->getCity() == $city['city_code'])? ' selected="selected" ' : '');
                $data['city_options'] .= '>'.$city['name'];
                $data['city_options'] .= ( $city['lang_code_2a'] != $this->session->getLanguageCode2a() )? ' ['.$city['lang_code_2a'].']' : '';
                $data['city_options'] .= '</option>';
            }
        }
        else
        {
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error('API Error ');
            $this->logger_err->error('URL ('.$route.')');
            $this->logger_err->error('data_to_api => '.print_r($array_sent ));
            $this->logger_err->error('Error Msg -> '.( empty( $api_response ) )? 'No response from API' : $api_response['msg'].')');
            $this->logger_err->error('*************************************************************************');

            $data['city_options'] .= '<option value="" selected="selected">'.$this->lang['REGION_CITIES_NOT_FOUND'].'</option>';
        }
    }
