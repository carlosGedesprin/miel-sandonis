<?php

namespace src\controller\entity\repository;

use \src\controller\entity\blogCategoryController;
use \src\controller\entity\blogArticleLangController;

use src\controller\entity\langTextController;
use src\controller\entity\langTextNameController;

/**
 * Trait blog article
 * @package entity
 */
trait blogArticleRepositoryController
{
    /**
     *
     * Return options list for blog articles
     */
    public function getOrdinalOptionsList( $action, $value_selected )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $lang_text = new langTextController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $lang_text_name = new langTextNameController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $blog_article_lang = new blogArticleLangController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $blog_articles = $this->getAll( NULL, ' ORDER BY `ordinal` ASC' );
        if ( sizeof( $blog_articles ) )
        {
            $last_article = ( sizeof( $blog_articles ) )? sizeof( $blog_articles ) - 1 : 0;
            $last_ordinal = $blog_articles[$last_article]['ordinal'];
        }
        else
        {
            $last_ordinal = 0;
        }

        $options_list = '';

        if ( $action == 'delete' )
        {
            $options_list .= '<option value="'.$value_selected.'" >'.$this->getTitle().'</option>';
        }
        else
        {
            $lang_text->getRegbyLangKey( 'ORDINAL_ADD_HERE', );
            $lang_text_name->getRegbyLangTextAndLang( $lang_text->getId(), $this->session->getLanguageCode2a() );
            $text_add = $lang_text_name->getText();
//$txt = 'Text add ===> '.$text_add.PHP_EOL; fwrite($this->myfile, $txt);
            //$text_add = langTextController::getLangText( $this->utils, $locale,'ORDINAL_ADD_HERE' );
            $lang_text->getRegbyLangKey( 'ORDINAL_MOVE_HERE', );
            $lang_text_name->getRegbyLangTextAndLang( $lang_text->getId(), $this->session->getLanguageCode2a() );
            $text_no_add = $lang_text_name->getText();
//$txt = 'Text no add ===> '.$text_no_add.PHP_EOL; fwrite($this->myfile, $txt);
            //$text_no_add = langTextController::getLangText( $this->utils, $locale,'ORDINAL_MOVE_HERE' );

            foreach ( $blog_articles as $key_article => $value_article )
            {
                if ( $key_article == 0 && $value_selected != 10 )
                {
                    $options_list .= '<option value="5">'.str_repeat('&#x2500', 20).'> ';
                    $options_list .= ( ( $action == 'add' )? '+ '.$text_add : '<< '.$text_no_add );
                    $options_list .= ( $_ENV['env_env'] != 'dev' )? '' : ' 5';
                    $options_list .= '</option>';
                }

                $blog_article_lang->getRegbyArticleLang( $value_article['id'], $this->session->getLanguageCode2a() );
                $options_list .= '<option value="'.$value_article['ordinal'].'"'.(( $value_selected == $value_article['ordinal'])? ' selected="selected" ' : '').' >';
                $options_list .= $blog_article_lang->getTitle();
                $options_list .= ( $_ENV['env_env'] != 'dev' )? '' : ' '.$value_article['ordinal'];
//                $options_list .= $value['title'].' '.$key.' '.$last_section.' '.$value['ordinal'].' '.$value_selected.' '.$last_ordinal;
                $options_list .= '</option>';

                if ( $value_selected == $value_article['ordinal'] && $value_selected == $last_ordinal )
                {
                    $options_list .= '<option value="'.($value_article['ordinal'] + 5).'">'.str_repeat('&#x2500', 20).'> ';
                    $options_list .= ( ( $action == 'add' )? '+ '.$text_add : '<< '.$text_no_add );
                    $options_list .= ( $_ENV['env_env'] != 'dev' )? '' : ' '.($value_article['ordinal'] + 5);
                    $options_list .= '</option>';
                }
                else
                {
                    $options_list .= '<option value="'.($value_article['ordinal'] + 5).'">'.str_repeat('&#x2500', 20).'> ';
                    $options_list .= ( ( $action == 'add' )? '+ '.$text_add : '<< '.$text_no_add );
                    $options_list .= ( $_ENV['env_env'] != 'dev' )? '' : ' '.($value_article['ordinal'] + 5);
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
        $blog_category = new blogCategoryController( array( 'env' => $_ENV, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils,'session' => $this->session,'lang' => $this->lang ) );

        $blog_categories = $blog_category->getAll();

        $order = 10;

        foreach ( $blog_categories as $key_category => $value_category )
        {
            $blog_category->getRegbyId( $value_category['id'] );

            $blog_articles = $this->getAll( ['category' => $blog_category->getId()], ' ORDER BY `ordinal` ASC' );

            foreach ( $blog_articles as $key_article => $value_article )
            {
                $this->getRegbyId( $value_article['id'] );
//$txt = 'Article ========== '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

//$txt = 'Old ordinal ===== '.$this->getOrdinal().' new '.$order.PHP_EOL; fwrite($this->myfile, $txt);
                $this->setOrdinal( $order );
                $this->persist();

                $order = $order + 10;
//$txt = 'New ordinal ========== '.$order.PHP_EOL; fwrite($this->myfile, $txt);
            }

            $order = 10;
        }
//$txt = 'Items ========== '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($items, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }
}
