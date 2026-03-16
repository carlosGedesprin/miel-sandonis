<?php
//$myfile_region_country_all = fopen(APP_ROOT_PATH.'/var/logs/viewSelects_RegionCountryAll.txt', 'w') or die('Unable to open file!');
//$txt = 'viewSelects_Region_Country_All start ==============================================================='.PHP_EOL; fwrite($myfile_region_country_all, $txt);

    if ( $reg->getCountry() == '')
    {
        $data['region_options'] .= '<option value="" selected="selected"><b>'.$this->lang['COUNTRY_SELECT'].'</b></option>';
    }
    else
    {
    //$txt = 'Country ========== ('.$reg->getCountry().')'.PHP_EOL; fwrite($myfile_country_profile, $txt);
        $api_data = array(
                            'data' => array(
                                            'api_key' => $this->session->config['locations_api_key'],
                                            'lang' => $this->session->getLanguageCode2a(),
                                            'country' => $reg->getCountry(),
                            )
        );
//$txt = 'Api data =========='.PHP_EOL; fwrite($myfile_country_profile, $txt);
//fwrite($myfile_country_profile, print_r($api_data, TRUE)); $txt = PHP_EOL; fwrite($myfile_country_profile, $txt);
        $route = '/get_regions';
        $api_response = $this->utils->get_from_locations_api( $route, $api_data );
//$txt = 'Api response =========='.PHP_EOL; fwrite($myfile_country_profile, $txt);
//fwrite($myfile_country_profile, print_r($api_response, TRUE)); $txt = PHP_EOL; fwrite($myfile_country_profile, $txt);

        if ( $api_response['status'] == 'OK')
        {
            if ( $reg->getRegion() == '' )
            {
                $data['region_options'] .= '<option value=""' . (($reg->getRegion() == '') ? ' selected="selected" ' : '') . '>' . $this->lang['REGION_SELECT'] . '</option>';
                $data['region_options'] .= '<option disabled>' . str_repeat('&#x2500', 16) . '</option>';
            }
            foreach ( $api_response['msg'] as $key => $region)
            {
                $data['region_options'] .= '<option';
                $data['region_options'] .= ' value="'.$region['region_code'].'"';
                $data['region_options'] .= (($reg->getRegion() == $region['region_code'])? ' selected="selected" ' : '');
                $data['region_options'] .= '>'.$region['name'];
                $data['region_options'] .= ( $region['lang_code_2a'] != $this->session->getLanguageCode2a() )? ' ['.$region['lang_code_2a'].']' : '';
                $data['region_options'] .= '</option>';
            }
        }
        else
        {
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error('API Error ');
            $this->logger_err->error('URL ('.$url.')');
            $this->logger_err->error('Error Msg -> '.$api_response['msg'].')');
            $this->logger_err->error('*************************************************************************');

            $data['region_options'] .= '<option value="" selected="selected">'.$this->lang['CITY_NOT_FOUND'].'</option>';
        }
    }
