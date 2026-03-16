<?php
namespace src\util\utils;

/**
 * Trait form_token.php
 * @package Utils
 */
trait form_token
{
    /**
     * Create a CSRF token
     *
     * @param string $form_action  Unique name of the form
     * @return string       Token
     */
    public function generateFormToken( $form_action )
    {

        $token = md5(uniqid(microtime(), true));
        $token_time = time();
        //echo '<pre>';echo 'Form ('.$form_action.')<br />';echo 'Token ('.$token.')<br />';echo 'Token time ('.$token_time.')';echo '</pre>';
        //echo print '<pre>';echo 'Token ('.$token.')<br /> ';echo 'Token time ('.$token_time.')<br /> ';print '</pre>';echo '<br />';
        // clean old tokens and write the token into the session
        unset($_SESSION['csrf']);
        $_SESSION['csrf'][$form_action.'_token'] = array('token'=>$token, 'time'=>$token_time);
        //echo print '<pre>';echo 'Token generated for '.$form_action.' <br /> ';print_r($_SESSION['csrf']);print '</pre>';echo '<br />';

        return $token;
    }

    /**
     * Verify CSRF token
     *
     * @param string $form_action          Unique name of the form
     * @param string $token                Form token
     * @param int $delta_time       Max time form token is valid
     * @return bool                 Result verification
     */
    public function verifyFormToken($form_action, $token, $delta_time=0)
    {
        if( !isset($_SESSION['csrf'][$form_action.'_token']) )
        {
            if ( $_ENV['env_env'] == 'dev' )
            {
                /*
                $now_is = (new \DateTime("now", new \DateTimeZone($_ENV['time_zone'])))->format('d-m-Y H:i:s');
                $myfile = fopen(APP_ROOT_PATH.'/var/logs/debug_utils_auth_token.txt', 'a+') or die('Unable to open file!');
                $txt = 'utilsController verifyFormToken start '.$now_is.'==============================================================='.PHP_EOL; fwrite($myfile, $txt);
                if ( isset($_SESSION['csrf']) )
                {
                    $txt = 'No está en la session SESSION["csrf"]'.PHP_EOL; fwrite($myfile, $txt);
                }
                else
                {
                    $txt = 'No está en session SESSION["csrf"][$form_action."_token"]'.PHP_EOL; fwrite($myfile, $txt);
                }
                $txt = '================================================================================================================'.PHP_EOL; fwrite($myfile, $txt);
                fclose($myfile);
                */
            }
            return false;
        }

        if ( $_SESSION['csrf'][$form_action.'_token']['token'] !== $token )
        {
            if ( $_ENV['env_env'] == 'dev' )
            {
                /*
                $now_is = (new \DateTime("now", new \DateTimeZone($_ENV['time_zone'])))->format('d-m-Y H:i:s');
                $myfile = fopen(APP_ROOT_PATH.'/var/logs/debug_utils_auth_token.txt', 'a+') or die('Unable to open file!');
                $txt = 'utilsController verifyFormToken start '.$now_is.'==============================================================='.PHP_EOL; fwrite($myfile, $txt);
                $txt = 'No son iguales ('.$_SESSION['csrf'][$form_action.'_token']['token'].') ('.$token.')'.PHP_EOL; fwrite($myfile, $txt);
                $txt = '================================================================================================================'.PHP_EOL; fwrite($myfile, $txt);
                fclose($myfile);
                */
            }
            return false;
        }

        if( $delta_time > 0 )
        {
            $token_age = time() - $_SESSION['csrf'][$form_action.'_token']['time'];
            if( $token_age >= $delta_time )
            {
                if ( $_ENV['env_env'] == 'dev' )
                {
                    /*
                    $now_is = (new \DateTime("now", new \DateTimeZone($_ENV['time_zone'])))->format('d-m-Y H:i:s');
                    $myfile = fopen(APP_ROOT_PATH.'/var/logs/debug_utils_auth_token.txt', 'a+') or die('Unable to open file!');
                    $txt = 'utilsController verifyFormToken start '.$now_is.'==============================================================='.PHP_EOL; fwrite($myfile, $txt);
                    $txt = 'Es demasiado viejo.'.PHP_EOL; fwrite($myfile, $txt);
                    $txt = '================================================================================================================'.PHP_EOL; fwrite($myfile, $txt);
                    fclose($myfile);
                    */
                }
                return false;
            }
        }
        // Clean session tokens
        $_SESSION['csrf'] = '';

        return true;
    }

}
