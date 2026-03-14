<?php
$myfile_country_all = fopen(APP_ROOT_PATH.'/var/logs/viewSelects_CountryAll.txt', 'w') or die('Unable to open file!');
$txt = 'viewSelects_CountryAll start ==============================================================='.PHP_EOL; fwrite($myfile_country_all, $txt);

    $api_data = array(
                        'api_key' => $this->session->config['locations_api_key'],
                        'lang' => $this->session->getLanguageCode2a(),
    );
//$txt = 'Api data =========='.PHP_EOL; fwrite($myfile_country_all, $txt);
//fwrite($myfile_country_all, print_r($api_data, TRUE)); $txt = PHP_EOL; fwrite($myfile_country_all, $txt);
    $route = '/get_countries';
    $api_response = $this->utils->get_from_locations_api( $route, $api_data );
$txt = 'Api response =========='.PHP_EOL; fwrite($myfile_country_all, $txt);
fwrite($myfile_country_all, print_r($api_response, TRUE)); $txt = PHP_EOL; fwrite($myfile_country_all, $txt);

    if ( $api_response['status'] == 'OK')
    {
        if ( $reg->getCountry() == '' )
        {
            $data['country_options'] .= '<option value=""' . (($reg->getCountry() == '') ? ' selected="selected" ' : '') . '>' . $this->lang['COUNTRY_SELECT'] . '</option>';
            $data['country_options'] .= '<option disabled>' . str_repeat('&#x2500', 16) . '</option>';
        }
//$txt = 'Countries ========== ('.$reg->getCountry().')'.PHP_EOL; fwrite($myfile_country_all, $txt);
//fwrite($myfile_country_all, print_r($api_response['msg'], TRUE));$txt = PHP_EOL; fwrite($myfile_country_all, $txt);
        foreach ( $api_response['msg'] as $key => $country)
        {
            $data['country_options'] .= '<option';
            $data['country_options'] .= ' value="'.$country['country_code_2a'].'"';
            $data['country_options'] .= (($reg->getCountry() == $country['country_code_2a'])? ' selected="selected" ' : '');
            $data['country_options'] .= '>'.$country['name'];
            $data['country_options'] .= ( $country['lang_code_2a'] != $this->session->getLanguageCode2a() )? ' ['.$country['lang_code_2a'].']' : '';
            $data['country_options'] .= '</option>';
        }
    }
    else
    {
        $this->logger_err->error('*************************************************************************');
        $this->logger_err->error('API Error ');
        $this->logger_err->error('URL ('.$url_to_call.')');
        $this->logger_err->error('Error Msg -> '.$api_response['msg'].')');
        $this->logger_err->error('*************************************************************************');

        $data['country_options'] .= '<option value="" selected="selected">'.$this->lang['COUNTRY_NOT_FOUND'].'</option>';
    }
$txt = 'viewSelects_CountryAll end ==============================================================='.PHP_EOL; fwrite($myfile_country_all, $txt);