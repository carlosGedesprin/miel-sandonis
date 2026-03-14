<?php
//$myfile_iban_all = fopen(APP_ROOT_PATH.'/var/logs/viewSelects_IbanAll.txt', 'w') or die('Unable to open file!');
//$txt = '====================== start ==============================================================='.PHP_EOL; fwrite($myfile_iban_all, $txt);

    $url_to_call = $this->session->config['locations_api'].'/get_banks';
//$txt = 'Url to call =============================> '.$url_to_call.PHP_EOL; fwrite($myfile_iban_all, $txt);

    $data_to_api = array(
                        'data' => array(
                                            'api_key' => $_ENV['locations_api_key'],
                                            'country_2a' => $_ENV['country']
                                        )
    );
    $api_response = $this->utils->send_to_api( $url_to_call, $data_to_api );
//$txt = 'Api response ========== >'.PHP_EOL; fwrite($myfile_iban_all, $txt);
//fwrite($myfile_iban_all, print_r($api_response, TRUE));$txt = PHP_EOL; fwrite($myfile_iban_all, $txt);

    if ( $api_response['status'] == 'OK')
    {
        if ( $reg->getIban() == '' )
        {
            $data['iban_options'] .= '<option value=""' . (($reg->getIban() == '') ? ' selected="selected" ' : '') . '>' . $this->lang['BANK_ACCOUNT_IBAN_SELECT'] . '</option>';
            $data['iban_options'] .= '<option disabled>' . str_repeat('&#x2500', 16) . '</option>';
        }
//$txt = 'Banks ========== ('.$reg->getIban().')'.PHP_EOL; fwrite($myfile_iban_all, $txt);
//fwrite($myfile_iban_all, print_r($api_response['message'], TRUE));$txt = PHP_EOL; fwrite($myfile_iban_all, $txt);
        foreach ( $api_response['message'] as $key => $bank)
        {
            $data['iban_options'] .= '<option';
            $data['iban_options'] .= ' value="'.$bank['iban'].'"';
            $data['iban_options'] .= (($reg->getIban() == $bank['iban'])? ' selected="selected" ' : '');
            $data['iban_options'] .= '>'.$bank['iban'].' - '.$bank['name'];
            $data['iban_options'] .= '</option>';
        }
    }
    else
    {
        $this->logger_err->error('*************************************************************************');
        $this->logger_err->error('API Error ');
        $this->logger_err->error('URL ('.$url_to_call.')');
        $this->logger_err->error('Error Msg -> '.$api_response['message'].')');
        $this->logger_err->error('*************************************************************************');

        $data['country_options'] .= '<option value="" selected="selected">'.$this->lang['BANKS_ACCOUNT_NOT_FOUND'].'</option>';
    }
//$txt = '====================== end ==============================================================='.PHP_EOL; fwrite($myfile_iban_all, $txt);