<?php
namespace src\util;

use \src\controller\entity\langTextController;
use \src\controller\entity\langTextNameController;

class lang
{
    private $env;
    private $logger;
    private $logger_err;
    private $startup;
    private $db;
    private $utils;
    private $session;

    private $table = 'lang_text';

    private $reg = array(
                            //'id'           => '',
                            'lang_code_2a'  => '',
                            'context'       => '',
                            'lang_key'      => '',
                            'text'          => '',
    );

    private $lang_code_2a;

    private $myfile;

    public function __construct( $env, $logger, $logger_err, $startup, $db, $utils, $session )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/lang.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $this->env = $env;
        $this->logger = $logger;
        $this->logger_err = $logger_err;
        $this->startup = $startup;
        $this->db = $db;
        $this->utils = $utils;
        $this->session = $session;
        
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }
    
    /**
     * Gets all texts from a language and creates the lang array
     * used every where
     *
     * Called from web/app.php
     */
    public function getLangTexts()
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $lang_text = new langTextController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session ) );
        $lang_text_name = new langTextNameController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session ) );

//$txt = 'Lang code 2a ('.$this->session->getLanguageCode2a().')'.PHP_EOL; fwrite($this->myfile, $txt);

        $lang = array();

        $lang_texts = $lang_text->getAll();
//fwrite($this->myfile, print_r($lang_texts, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        foreach ( $lang_texts as $key => $lang_text_temp )
        {
            $lang_text_name->getRegbyLangTextAndLang( $lang_text_temp['id'], $this->session->getLanguageCode2a() );

            $lang[$lang_text_temp['lang_key']] = $lang_text_name->getText();
        }

//fwrite($this->myfile, print_r($lang, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $lang;
    }
}
