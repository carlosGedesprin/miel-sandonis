<?php

namespace src\controller;

use DateTime;
use DateTimeZone;
use Exception;

class testn8nController extends baseController
{
    /**
     * Test n8n webhook
     *
     */
    public function testWebHook( $vars )
    {
$this->myfile = fopen(APP_ROOT_PATH . '/var/logs/testController_' . __FUNCTION__ . '.txt', 'w') or die('Unable to open file!');
$txt = '====================== ' . __METHOD__ . ' start ===============================================================' . PHP_EOL; fwrite($this->myfile, $txt);

$txt = 'Param ('.$vars['param'].')'.PHP_EOL; fwrite($this->myfile, $txt);

        $webhook_url = $_ENV['n8n_api'].'/webhook-test/webhook-1';
        $data = [
            "nombre" => "Juan",
            "email" => "juan@example.com",
            "mensaje" => "Hola desde PHP"
        ];

        $ch = curl_init($webhook_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);

        curl_setopt($ch, CURLOPT_TIMEOUT, 5); // max execution time in seconds

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ( $error )
        {
            $response = "Error de conexión o timeout: $error";
        }
        elseif ( $http_code !== 200 )
        {
            $response = "El webhook respondió con código HTTP $http_code. Se esperaba 200.";
        }
        else
        {
            $response = "Webhook respondió correctamente: $response";
        }

$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
fclose($this->myfile);
        echo $response;
    }
}