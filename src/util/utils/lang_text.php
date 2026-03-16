<?php
namespace src\util\utils;

use \src\controller\entity\langTextNameController;

/**
 * Trait lang text
 * @package Utils
 */
trait lang_text
{
    /**
     * Get the lang text name
     *
     * @param $lang_key      string   The requested lang key id
     * @param $lang_2a       string   Language in which the request has to be answered
     * @return string name   Name of the text
     */
    public function getLangTextName( $lang_key, $lang_2a )
    {
//$myfile = fopen(APP_ROOT_PATH.'/var/logs/utils_lang_text_'.__FUNCTION__.'.txt', 'a+') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($myfile, $txt);
        $lang_text_name = new langTextNameController( array( 'env' => $_ENV, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => array(), 'db' => $this->db, 'utils' => array(), 'session' => array(), 'lang' => array() ) );

        $name = $lang_text_name->getRegbyLangTextAndLang( $lang_key, $lang_2a );
// $txt = 'Key => '.$lang_key.' Lang => '.$lang_2a.' Name ==> '.$name.PHP_EOL . PHP_EOL; fwrite($myfile, $txt);
        if ( !empty( $name ) )
        {
            $result = $name;
        }
        else
        {
            $result = '---';
        }
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($myfile, $txt);
	    return $result;
    }
}
