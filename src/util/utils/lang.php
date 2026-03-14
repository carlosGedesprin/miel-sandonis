<?php
namespace src\util\utils;

/**
 * Trait lang
 * @package Utils
 */
trait lang
{
    /**
     * Check if lang is active
     *
     * @param $lang_code_2a     The requested language name code 2a
     * @return string name      Name of the language
     */
    public function checkLangActive( $lang_code_2a )
    {
        $result = $this->db->fetchField('lang', 'active', ['code_2a' => $lang_code_2a]);

        return ( $result == '1' )? true : false;
    }
    /**
     * Get default lang
     *
     * @param $lang_code_2a     The requested language name code 2a
     * @return string name      Name of the language
     */
    public function getDefaultLang()
    {
        return $this->db->fetchField('lang', 'code_2a', ['default' => '1']);
    }
    /**
     * Get the lang name
     *
     * @param $lang_code_2a     The requested language name code 2a
     * @param $lang             Language in which the request has to be answered
     * @return string name      Name of the language
     */
    public function getLangName( $lang_code_2a, $lang )
    {
        return $this->db->fetchField('lang_name', 'name', ['lang_code_2a' => $lang_code_2a, 'lang_2a' => $lang]);
    }
}
