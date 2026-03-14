<?php

//**************************************************************************
// Assets Versioned
//**************************************************************************
$asset_version = new \Twig\TwigFunction('asset_version', function( $relativePath ){

//$myfile = fopen(APP_ROOT_PATH.'/var/logs/twigFunctions_asset_version.txt', 'a+') or die('Unable to open file!');
//$txt = '====================== start ==============================================================='.PHP_EOL; fwrite($myfile, $txt);
//$txt = 'Param ('.$relativePath.')'.PHP_EOL; fwrite($myfile, $txt);

    $absolutePath = APP_ROOT_PATH.DIRECTORY_SEPARATOR.'web'.str_replace( 'assets', 'bundles/framework', $relativePath );
//$txt = 'Absolute path ('.$absolutePath.')'.PHP_EOL; fwrite($myfile, $txt);
    if ( file_exists( $absolutePath ) )
    {
//$txt = 'Return 1 ('.$relativePath.'?v='.filemtime($absolutePath).')'.PHP_EOL; fwrite($myfile, $txt);
//fclose($myfile);
        return $relativePath.'?v='.filemtime($absolutePath);
    }
//$txt = 'Return 2 ('.$relativePath.'?v='.filemtime($absolutePath).')'.PHP_EOL; fwrite($myfile, $txt);
//fclose($myfile);
    return $relativePath;
});
$twig->addFunction( $asset_version );

//**************************************************************************
// MESSAGES
//**************************************************************************
$function_deleteMessages = new \Twig\TwigFunction('deleteMessages', function(){
    //$_SESSION['alert'] = '';
    unset($_SESSION['alert']);
});
$twig->addFunction($function_deleteMessages);

//**************************************************************************
// GROUP
//**************************************************************************

$function_groupFolder = new \Twig\TwigFunction('groupFolder', function( $group ){
    global $db;
    return $db->fetchField('group', 'folder', ['id' => $group]);
});
$twig->addFunction($function_groupFolder);

//**************************************************************************
// LANG
//**************************************************************************
$function_getLangName = new \Twig\TwigFunction('getLangName', function($lang_code_2a, $lang_2a){
    global $db, $lang;

//$myfile = fopen(APP_ROOT_PATH.'/var/logs/debug_twigFunctions.txt', 'w') or die('Unable to open file!');
//$txt = 'twigFunctions getLangName start ==============================================================='.PHP_EOL; fwrite($myfile, $txt);
//$txt = 'LangCode2a ('.$lang_code_2a.') langname ('.$lang_2a.')'.PHP_EOL; fwrite($myfile, $txt);
    if ( !$name = $db->fetchField('lang_name', 'name', ['lang_code_2a' => $lang_code_2a, 'lang_2a' => $lang_2a]) )
    {
        $default_lang_code_2a = $db->fetchField('lang', 'code_2a', ['default' => '1']);

//$txt = 'LangCode2a ('.$lang_code_2a.') langname ('.$default_lang_code_2a.')'.PHP_EOL; fwrite($myfile, $txt);
        if ( !$name = $db->fetchField('lang_name', 'name', ['lang_code_2a' => $lang_code_2a, 'lang_2a' => $default_lang_code_2a]) )
        {
            $name = $lang['LANG_TEXT_NAME_NOT_TRANSLATED'];
        }
        $name .= ' ('.$default_lang_code_2a.')';
    }
//$txt = 'Name => '.$name.PHP_EOL; fwrite($myfile, $txt);
    return $name;
});
$twig->addFunction($function_getLangName);

//**************************************************************************
// LANG TEXT
//**************************************************************************
/**
 * Get the lang text name
 *
 * @param $lang_key      string   The requested lang key id
 * @param $lang_2a       string   Language in which the request has to be answered
 * @return string name   Name of the text
 */
$function_getLangTextName = new \Twig\TwigFunction('getLangTextName', function( $lang_key, $lang_code_2a ){
    global $db, $lang;

//$myfile = fopen(APP_ROOT_PATH.'/var/logs/twigFunctions_getLangTextName.txt', 'w') or die('Unable to open file!');
//$txt = '====================== twigFunctions getLangTextName start ==============================================================='.PHP_EOL; fwrite($myfile, $txt);
//$txt = 'Lang key ('.$lang_key.') lang code 2a ('.$lang_code_2a.')'.PHP_EOL; fwrite($myfile, $txt);

    if ( $text_id = $db->fetchField('lang_text', 'id', ['lang_key' => $lang_key]) )
    {
//$txt = 'Text id ('.$text_id.')'.PHP_EOL; fwrite($myfile, $txt);
        if ( !$text_name = $db->fetchField('lang_text_name', 'text', ['lang_text' => $text_id, 'lang_code_2a' => $lang_code_2a]) )
        {
            $default_lang_code_2a = $db->fetchField('lang', 'code_2a', ['default' => '1']);

//$txt = ' Default lang ('.$default_lang_code_2a.')'.PHP_EOL; fwrite($myfile, $txt);
            if ( !$text_name = $db->fetchField('lang_text_name', 'text', ['lang_text' => $text_id, 'lang_code_2a' => $default_lang_code_2a]) )
            {
                $text_name = $lang['LANG_TEXT_NAME_NOT_TRANSLATED'].' ('.$default_lang_code_2a.')';
            }
//            else
//            {
//$txt = 'Text name ('.$text_name.')'.PHP_EOL; fwrite($myfile, $txt);
//            }
        }
//        else
//        {
//$txt = 'Text name ('.$text_name.')'.PHP_EOL; fwrite($myfile, $txt);
//        }
//$txt = 'Text name ('.$text_id.')'.PHP_EOL; fwrite($myfile, $txt);
    }
    else
    {
        $text_name = '---> Text key not found';
    }
//$txt = 'Key => '.$lang_key.' Lang => '.$lang_code_2a.' Name ==> '.$text_name.PHP_EOL . PHP_EOL; fwrite($myfile, $txt);
//$txt = '====================== twigFunctions getLangTextName end ==============================================================='.PHP_EOL; fwrite($myfile, $txt);
    return $text_name;
});
$twig->addFunction($function_getLangTextName);
/* -------------------------------------------------------------------------------------- */
