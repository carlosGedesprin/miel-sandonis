<?php
namespace src\util\utils;

/**
 * Trait external_api
 * @package Utils
 */
trait external_api
{
    /**
     *  Connect with external apis
     */
    public function send_to_api( $url_to_call, $data, $authorization=NULL )
    {
//$myfile = fopen(APP_ROOT_PATH.'/var/logs/util_'.__FUNCTION__.'.txt', 'a+') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($myfile, $txt);
//$txt = 'Send to ========>'.$url_to_call.PHP_EOL.PHP_EOL; fwrite($myfile, $txt);
//$txt = 'Data ========>'. PHP_EOL . PHP_EOL; fwrite($myfile, $txt);
//fwrite($myfile, print_r($data, TRUE));
//if ( $authorization ) $txt = 'Auth ========>'.$authorization. PHP_EOL . PHP_EOL; fwrite($myfile, $txt);

        $json_post = json_encode( $data );
//$txt = 'Post in json ========> ('.$json_post.')'.PHP_EOL . PHP_EOL; fwrite($myfile, $txt);

        $curl_cmd = "curl -X POST ".
            escapeshellarg($url_to_call)." ".
            "-H 'Content-Type: application/json' ".
            "-H 'Accept: application/json' ";

        if ( $authorization )
        {
            $curl_cmd .= "-H 'Authorization: Bearer ".$authorization."' ";
        }
        $curl_cmd .= "-d '".addslashes($json_post)."'";

        //fwrite($myfile, "Curl command ========> ".$curl_cmd.PHP_EOL.PHP_EOL);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url_to_call);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_post );
        $headers = array(
            "Content-Type: application/json",
            "Accept: application/json",
        );
        if ( $authorization ) $headers[] = 'Authorization: Bearer '.$authorization;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //for debug only!
        //curl_setopt($ch, CURLOPT_SSLVERSION, 3);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        //curl_setopt($ch, CURLOPT_VERBOSE, true);
        //curl_setopt($ch, CURLOPT_STDERR, $myfile);

        $response = curl_exec($ch);

        //$info = curl_getinfo($ch);
        //fwrite($myfile, "Curl info ========> ".print_r($info, true).PHP_EOL);

        curl_close($ch);
//$txt = 'Response ========> ('.$response.')'.PHP_EOL . PHP_EOL; fwrite($myfile, $txt);
/*
        $jsonData = array(
            'origin' => $_SERVER['SERVER_NAME'],
            //'lang' => $this->session->getLanguageCode2a(),
            'lang' => $_SERVER["HTTP_ACCEPT_LANGUAGE"],
            //'auth_token' => $token,
            //'uri' => $uri,
            'post' => $post,
        );
//$txt = 'Encoded data sent to ('.$url_to_call.') ========>' . PHP_EOL . PHP_EOL; fwrite($myfile, $txt);
//fwrite($myfile, print_r($jsonData, TRUE));
        $jsonDataEncoded = json_encode($jsonData);
        $response = file_get_contents( $url_to_call, null, stream_context_create(array(
            'http' => array(
                'protocol_version' => 1.1,
                'user_agent'       => 'Accedeme_Agent',
                'method'           => 'POST',
                'header'           => "Content-type: application/json\r\n".
                    "Connection: close\r\n" .
                    "Accept-language: en\r\n" .
                    "Accept: ".$_SERVER["HTTP_ACCEPT"]."\r\n" .
                    "Accept-encoding: ".$_SERVER["HTTP_ACCEPT_ENCODING"]."\r\n" .
                    "Content-length: " . strlen($jsonDataEncoded) . "\r\n",
                'content'          => $jsonDataEncoded,
            ),
            'ssl' => array(
                'verify_peer'      => false,
                'verify_peer_name' => false,
            ),
        )));
*/
        $response = json_decode($response, true);
//$txt = 'Response ========>'.PHP_EOL . PHP_EOL; fwrite($myfile, $txt);
//fwrite($myfile, print_r($response, TRUE));

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($myfile, $txt);
//fclose($myfile);
        return $response;
    }

    public function outboundTest($remoteHostURL)
    {
        // Set the result array:
        $testResult = array();

        // Parse remote host URL:
        $parse = parse_url($remoteHostURL);

        // Get the remote host IP:
        $testResult['REMOTE_HOST_IP'] = gethostbyname($parse['host']);

        // Get the local IP address:
        $testResult['LOCAL_HOST_IP'] = file_get_contents('http://ipecho.net/plain');

        // Attempt to get remote contents using SSL:
        $testResult['FILE_GET_CONTENTS_SSL'] = file_get_contents($remoteHostURL);

        $testResult['Response headers SSL'] = print_r($http_response_header, TRUE);

        // Attempt to get remote contents using WITHOUT SSL:
        $contextOptions = [
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
            ],
        ];
        $testResult['FILE_GET_CONTENTS_NO_SSL'] = file_get_contents($remoteHostURL, FALSE, stream_context_create($contextOptions));

        $testResult['Response headers NO SSL'] = print_r($http_response_header, TRUE);

        // Try to get remote contents via CURL:
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $remoteHostURL,
        ]);
        $testResult['CURL_SSL'] = curl_exec($curl);
        curl_close($curl);

        // Try to get remote contents via CURL WITHOUT SSL:
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $remoteHostURL,
        ]);
        $testResult['CURL_NO_SSL'] = curl_exec($curl);
        curl_close($curl);

        // Get the stream wrappers:
        $testResult['STREAM_WRAPPERS'] = stream_get_wrappers();

        return $testResult;
    }
}
