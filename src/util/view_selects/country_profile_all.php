<?php
//$myfile_country_profile = fopen(APP_ROOT_PATH.'/var/logs/viewSelects_Country_Profile_All.txt', 'w') or die('Unable to open file!');
//$txt = '====================== start ==============================================================='.PHP_EOL; fwrite($myfile_country_profile, $txt);

    $api_data = array(
                        'data' => array(
                            'api_key' => $this->session->config['locations_api_key'],
                            'lang' => $this->session->getLanguageCode2a(),
                        )
    );
//$txt = 'Api data =========='.PHP_EOL; fwrite($myfile_country_profile, $txt);
//fwrite($myfile_country_profile, print_r($api_data, TRUE)); $txt = PHP_EOL; fwrite($myfile_country_profile, $txt);
    $route = '/get_countries';
    $api_response = $this->utils->get_from_locations_api( $route, $api_data );
//$txt = 'Api response =========='.PHP_EOL; fwrite($myfile_country_profile, $txt);
//fwrite($myfile_country_profile, print_r($api_response, TRUE)); $txt = PHP_EOL; fwrite($myfile_country_profile, $txt);

    if ( $api_response && $api_response['status'] == 'OK' )
    {
//$txt = 'Api response status ====> OK'.PHP_EOL; fwrite($myfile_country_profile, $txt);
        if ( $userProfile->getCountry() == '' )
        {
            $data['country_options'] .= '<option value=""'.(($userProfile->getCountry() == '')? ' selected="selected" ' : '').'>'.$this->lang['COUNTRY_SELECT'].'</option>';
            $data['country_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        }
//$txt = 'Countries ========== ('.$userProfile->getCountry().')'.PHP_EOL; fwrite($myfile_country_profile, $txt);
//fwrite($myfile_country_profile, print_r($api_response['msg'], TRUE));$txt = PHP_EOL; fwrite($myfile_country_profile, $txt);
        foreach ( $api_response['msg'] as $key => $country )
        {
            $data['country_options'] .= '<option';
            $data['country_options'] .= ' value="'.$country['country_code_2a'].'"';
            $data['country_options'] .= (($userProfile->getCountry() == $country['country_code_2a'])? ' selected="selected" ' : '');
            $data['country_options'] .= '>'.$country['name'];
            $data['country_options'] .= ( $country['lang_code_2a'] != $this->session->getLanguageCode2a() )? ' ['.$country['lang_code_2a'].']' : '';
            $data['country_options'] .= '</option>';
        }
    }
    else
    {
//$txt = 'Api response status ====> NOT OK'.PHP_EOL; fwrite($myfile_country_profile, $txt);
        $this->logger_err->error('*************************************************************************');
        $this->logger_err->error('Called by ('.__FUNCTION__.')');
        $this->logger_err->error('API Error ');
        $this->logger_err->error('URL ('.$route.')');
        $this->logger_err->error('Error Msg -> '.( isset($api_response) )? $api_response['msg'] : 'No Api response'.')');
        $this->logger_err->error('*************************************************************************');

        $data['country_options'] .= '<option value="" selected="selected">'.$this->lang['COUNTRY_NOT_FOUND'].'</option>';
    }
//$txt = 'Data ====> '.$data['country_options'].PHP_EOL; fwrite($myfile_country_profile, $txt);
//$txt = '====================== end ==============================================================='.PHP_EOL; fwrite($myfile_country_profile, $txt);
//fclose( $myfile_country_profile );