<?php

namespace src\controller\entity\repository;

use src\controller\entity\langTextController;
use src\controller\entity\langTextNameController;

/**
 * Trait blog category
 * @package entity
 */
trait blogCategoryRepositoryController
{
    /**
     *
     * Return options list for blog categories
     */
    public function getOrdinalOptionsList( $action, $value_selected )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $lang_text = new langTextController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $lang_text_name = new langTextNameController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $blog_categories = $this->getAll( NULL, ' ORDER BY `ordinal` ASC' );
        $last_category = sizeof( $blog_categories ) - 1;
        $last_ordinal = $blog_categories[$last_category]['ordinal'];
        $options_list = '';

        if ( $action == 'delete' )
        {
            $options_list .= '<option value="'.$value_selected.'" >'.$this->getTitle().'</option>';
        }
        else
        {
            $locale = $_SESSION[$this->session->config['cookies_prefix'].'_locale'];
//$txt = 'Locale ===> '.$locale.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($vars, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
            $lang_text->getRegbyLangKey( 'ORDINAL_ADD_HERE', );
            $lang_text_name->getRegbyLangTextAndLang( $lang_text->getId(), $locale );
            $text_add = $lang_text_name->getText();
//$txt = 'Text add ===> '.$text_add.PHP_EOL; fwrite($this->myfile, $txt);
            //$text_add = langTextController::getLangText( $this->utils, $locale,'ORDINAL_ADD_HERE' );
            $lang_text->getRegbyLangKey( 'ORDINAL_MOVE_HERE', );
            $lang_text_name->getRegbyLangTextAndLang( $lang_text->getId(), $locale );
            $text_no_add = $lang_text_name->getText();
//$txt = 'Text no add ===> '.$text_no_add.PHP_EOL; fwrite($this->myfile, $txt);
            //$text_no_add = langTextController::getLangText( $this->utils, $locale,'ORDINAL_MOVE_HERE' );

            foreach ( $blog_categories as $key_category => $value_category )
            {
                if ( $key_category == 0 && $value_selected != 10 )
                {
                    $options_list .= '<option value="5">'.str_repeat('&#x2500', 20).'> ';
                    $options_list .= ( ( $action == 'add' )? '+ '.$text_add : '<< '.$text_no_add );
                    $options_list .= ( $_ENV['env_env'] != 'dev' )? '' : ' 5';
                    $options_list .= '</option>';
                }

                $options_list .= '<option value="'.$value_category['ordinal'].'"'.(( $value_selected == $value_category['ordinal'])? ' selected="selected" ' : '').' >';
                $options_list .= $value_category['title'];
                $options_list .= ( $_ENV['env_env'] != 'dev' )? '' : ' '.$value_category['ordinal'];
//                $options_list .= $value['title'].' '.$key.' '.$last_section.' '.$value['ordinal'].' '.$value_selected.' '.$last_ordinal;
                $options_list .= '</option>';

                if ( $value_selected == $value_category['ordinal'] && $value_selected == $last_ordinal )
                {
                    $options_list .= '<option value="'.($value_category['ordinal'] + 5).'">'.str_repeat('&#x2500', 20).'> ';
                    $options_list .= ( ( $action == 'add' )? '+ '.$text_add : '<< '.$text_no_add );
                    $options_list .= ( $_ENV['env_env'] != 'dev' )? '' : ' '.($value_category['ordinal'] + 5);
                    $options_list .= '</option>';
                }
                else
                {
                    $options_list .= '<option value="'.($value_category['ordinal'] + 5).'">'.str_repeat('&#x2500', 20).'> ';
                    $options_list .= ( ( $action == 'add' )? '+ '.$text_add : '<< '.$text_no_add );
                    $options_list .= ( $_ENV['env_env'] != 'dev' )? '' : ' '.($value_category['ordinal'] + 5);
                    $options_list .= '</option>';
                }
            }
        }
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $options_list;
    }

    /**
     *
     * Re-order records by ordinal
     *
     * @return void
     */
    public function reOrderOrdinals()
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $items = $this->getAll( NULL, ' ORDER BY `ordinal` ASC' );
//$txt = 'Items ========== '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($items, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        $order = 10;

        foreach ( $items as $key => $value )
        {
            $this->getRegbyId( $value['id'] );
//$txt = 'Item ========== '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

            $this->setOrdinal( $order );
            $this->persist();

            $order = $order + 10;
//$txt = 'New ordinal ========== '.$order.PHP_EOL; fwrite($this->myfile, $txt);
        }
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }
}
