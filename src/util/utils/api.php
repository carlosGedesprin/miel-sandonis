<?php
namespace src\util\utils;

/**
 * Trait api
 * @package Utils
 */
trait api
{
    /**
     *
     */
    public function checkAPIRequest()
    {
//$myfile = fopen(APP_ROOT_PATH.'/var/logs/apiController_'.__FUNCTION__.'.txt', 'a+') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL;fwrite($myfile, $txt);

        $response = array(
                            'status' => '',
                            'data' => array(),
        );

//fwrite($myfile, print_r($_SERVER, TRUE));$txt = PHP_EOL; fwrite($myfile, $txt);


        // Make sure that it is a POST request.
        if ( strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') != 0 )
        {
            //throw new Exception('Request method must be POST!');
            $response['status'] = 'KO';
            $response['data']['error_code'] = '1';
            $response['data']['error_des'] = 'Request is not POST'; //$this->db->fetchField('lang_text', 'text', ['lang_code_2a' => 'en', 'lang_key' => 'ERR_REQUEST_NOT_POST']);
//$txt = 'Request method must be POST! ==============================================================='.PHP_EOL; fwrite($myfile, $txt);
//fwrite($myfile, print_r($_SERVER['REQUEST_METHOD'], TRUE));$txt = PHP_EOL; fwrite($myfile, $txt);
        }
        else
        {
//$txt = 'Request method is POST, great! ==============================================================='.PHP_EOL; fwrite($myfile, $txt);
            //Make sure that the content type of the POST request has been set to application/json
            $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
            if ( strcasecmp($contentType, 'application/json') != 0 )
            {
                //throw new Exception('Content type must be: application/json');
                $response['status'] = 'KO';
                $response['data']['error_code'] = '2';
                $response['data']['error_des'] = 'Request is not a JSON'; //$this->db->fetchField('lang_text', 'text', ['lang_code_2a' => 'en', 'lang_key' => 'ERR_REQUEST_NOT_JSON']);
                $response['data']['error_details'] = trim(file_get_contents("php://input"));;
//$txt = 'Content type must be: application/json ==============================================================='.PHP_EOL; fwrite($myfile, $txt);
//$txt = $contentType.PHP_EOL; fwrite($myfile, $txt);
            }
            else
            {
//$txt = 'Content type is application/json, great! ==============================================================='.PHP_EOL; fwrite($myfile, $txt);
                //Receive the RAW post data.
                $json_received = trim(file_get_contents("php://input"));

//fwrite($myfile, print_r($json_received, TRUE));$txt = PHP_EOL; fwrite($myfile, $txt);

                //Attempt to decode the incoming RAW post data from JSON.
                $array_received = json_decode($json_received, true);

                //If json_decode failed, the JSON is invalid.
                if( !is_array( $array_received ) )
                {
                    //throw new Exception('Received content contained invalid JSON!');
                    $response['status'] = 'KO';
                    $response['data']['error_code'] = '3';
                    $response['data']['error_des'] = 'Bad JSON received'; //$this->db->fetchField('lang_text', 'text', ['lang_code_2a' => 'en', 'lang_key' => 'ERR_REQUEST_BAD_JSON']);
                    $response['data']['error_details'] = $json_received;
//$txt = 'Received content contained invalid JSON! ==============================================================='.PHP_EOL; fwrite($myfile, $txt);
//fwrite($myfile, print_r($array_received, TRUE));$txt = PHP_EOL; fwrite($myfile, $txt);
                }
                else
                {
                    $response['status'] = 'OK';
                    $response['data'] = $array_received;
//$txt = 'Received content contained is a valid JSON! ==============================================================='.PHP_EOL; fwrite($myfile, $txt);
                }
            }
        }

        if ( $response['status'] == 'KO' )
        {
            $this->logger->error('*************************************************************************');
            $this->logger->error('API Error on '.debug_backtrace()[1]['function'].')');
            $this->logger->error('Error code '.$response['data']['error_code'].')');
            $this->logger->error('Description '.$response['data']['error_des'].')');
            if ( isset( $response['data']['error_details'] ) ) $this->logger->error('Details '.$response['data']['error_details'].')');
            $this->logger->error('*************************************************************************');
        }

//$txt = 'Response ==============================================================='.PHP_EOL; fwrite($myfile, $txt);
//fwrite($myfile, print_r($response, TRUE));$txt = PHP_EOL; fwrite($myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($myfile, $txt);
//fclose($myfile);
        return $response;
    }

    /**
     *  Send lang request to langs api
     *
     * @param $route     string   Route
     * @param $api_data  array    Data to send to route
     * @param $extra     string   Extra data to send to route
     * @return array     Array of api response
     */
    public function get_from_lang_api( $route, $api_data, $extra=NULL )
    {
//$myfile = fopen(APP_ROOT_PATH.'/var/logs/utils_lang_'.__FUNCTION__.'.txt', 'a+') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($myfile, $txt);

//$txt = 'Call to : '.$_ENV['lang_api_protocol'].'://'.$_ENV['lang_api_domain'].$route.PHP_EOL; fwrite($myfile, $txt);
//$txt = 'Post '.PHP_EOL; fwrite($myfile, $txt);
//fwrite($myfile, print_r($api_data, TRUE));
        $url_to_call = $_ENV['lang_api_protocol'].'://'.$_ENV['lang_api_domain'].$_ENV['lang_api_port'].$route;
        $response = $this->send_to_api( $url_to_call, $api_data, $extra );

//$txt = 'Response ======== >'.PHP_EOL . PHP_EOL; fwrite($myfile, $txt);
//fwrite($myfile, print_r($response, TRUE));
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($myfile, $txt);
//fclose($myfile);
        return $response;
    }

    /**
     *  Send country request to countries api
     *
     * @param $route     string   Route
     * @param $api_data  array    Data to send to route
     * @param $extra     string   Extra data to send to route
     * @return array     Array of api response
     */
    public function get_from_locations_api( $route, $api_data, $extra=NULL )
    {
//$myfile = fopen(APP_ROOT_PATH.'/var/logs/utils_'.__FUNCTION__.'.txt', 'a+') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($myfile, $txt);

//$txt = 'Call to : '.$_ENV['lang_api_protocol'].'://'.$_ENV['lang_api_domain'].$route.PHP_EOL; fwrite($myfile, $txt);
//$txt = 'Post '.PHP_EOL; fwrite($myfile, $txt);
//fwrite($myfile, print_r($api_data, TRUE));
        $url_to_call = $_ENV['locations_api'].$route;

        $api_data = array(
                            'data' => $api_data,
                            'extra' => $extra
        );

        $response = $this->send_to_api( $url_to_call, $api_data );

//$txt = 'Response ======== >'.PHP_EOL . PHP_EOL; fwrite($myfile, $txt);
//fwrite($myfile, print_r($response, TRUE));
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($myfile, $txt);
//fclose($myfile);
        return $response;
    }
}
