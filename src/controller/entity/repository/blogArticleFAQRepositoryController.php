<?php

namespace src\controller\entity\repository;

use \src\controller\entity\blogArticleController;

use src\controller\entity\langTextController;
use src\controller\entity\langTextNameController;

/**
 * Trait blog article FAQ
 * @package entity
 */
trait blogArticleFAQRepositoryController
{

    /**
     *
     * Get blog article faqsfrom his id and lang_code_2a
     *
     */
    public function getFaqsbyArticleLang( $article, $lang_code_2a )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Article ==> ('.$article.') Lang ==> ('.$lang_code_2a.')'.PHP_EOL; fwrite($this->myfile, $txt);
        if ( !$article ) return false;
        if ( !$lang_code_2a ) return false;

        $filter_select = array(
                                'article' => $article,
                                'lang_code_2a' => $lang_code_2a,
        );
//$txt = 'Article faqs filter select ========>' . PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($filter_select, TRUE));
        $extra_select = 'ORDER BY `ordinal`';
        $article_faqs = $this->getAll( $filter_select, $extra_select);
//$txt = 'Article faqs from db ========>' . PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($article_faqs, TRUE));
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $article_faqs;
    }
    /**
     *
     * Return options list for blog article FAQ
     */
    public function getOrdinalOptionsList( $action, $value_selected )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $lang_text = new langTextController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $lang_text_name = new langTextNameController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $blog_articles_faq = $this->getAll( NULL, ' ORDER BY `ordinal` ASC' );
        if ( sizeof( $blog_articles_faq ) )
        {
            $last_article_faq = ( sizeof( $blog_articles_faq ) )? sizeof( $blog_articles_faq ) - 1 : 0;
            $last_ordinal = $blog_articles_faq[$last_article_faq]['ordinal'];
        }
        else
        {
            $last_ordinal = 0;
        }

        $options_list = '';

        if ( $action == 'delete' )
        {
            $options_list .= '<option value="'.$value_selected.'" >'.$this->getQuestion().'</option>';
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

            foreach ( $blog_articles_faq as $key_article_faq => $value_article_faq )
            {
                if ( $key_article_faq == 0 && $value_selected != 10 )
                {
                    $options_list .= '<option value="5">'.str_repeat('&#x2500', 20).'> ';
                    $options_list .= ( ( $action == 'add' )? '+ '.$text_add : '<< '.$text_no_add );
                    $options_list .= ( $_ENV['env_env'] != 'dev' )? '' : ' 5';
                    $options_list .= '</option>';
                }

                $options_list .= '<option value="'.$value_article_faq['ordinal'].'"'.(( $value_selected == $value_article_faq['ordinal'])? ' selected="selected" ' : '').' >';
                $options_list .= $value_article_faq['question'];
                $options_list .= ( $_ENV['env_env'] != 'dev' )? '' : ' '.$value_article_faq['ordinal'];
//                $options_list .= $value['question'].' '.$key.' '.$last_section.' '.$value['ordinal'].' '.$value_selected.' '.$last_ordinal;
                $options_list .= '</option>';

                if ( $value_selected == $value_article_faq['ordinal'] && $value_selected == $last_ordinal )
                {
                    $options_list .= '<option value="'.($value_article_faq['ordinal'] + 5).'">'.str_repeat('&#x2500', 20).'> ';
                    $options_list .= ( ( $action == 'add' )? '+ '.$text_add : '<< '.$text_no_add );
                    $options_list .= ( $_ENV['env_env'] != 'dev' )? '' : ' '.($value_article_faq['ordinal'] + 5);
                    $options_list .= '</option>';
                }
                else
                {
                    $options_list .= '<option value="'.($value_article_faq['ordinal'] + 5).'">'.str_repeat('&#x2500', 20).'> ';
                    $options_list .= ( ( $action == 'add' )? '+ '.$text_add : '<< '.$text_no_add );
                    $options_list .= ( $_ENV['env_env'] != 'dev' )? '' : ' '.($value_article_faq['ordinal'] + 5);
                    $options_list .= '</option>';
                }
            }
        }
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $options_list;
    }

    /**
     *
     * Delete FAQ from a article
     *
     * @return void
     */
    public function deleteFAQs( $article_id )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $filter_select = array(
                                'article' => $article_id,
        );
        $extra_select = '';
        $rows = $this->getAll( $filter_select, $extra_select);
        foreach ( $rows as $row )
        {
            $this->getRegbyId( $row['id'] );
            $this->deleteORL();
        }
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
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
        $blog_article = new blogArticleController( array( 'env' => $_ENV, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils,'session' => $this->session,'lang' => $this->lang ) );

        $blog_articles = $blog_article->getAll();

        $order = 10;

        foreach ( $blog_articles as $key_article => $value_article )
        {
            $blog_article->getRegbyId( $value_article['id'] );

            $blog_articles_faq = $this->getAll( ['article' => $blog_article->getId()], ' ORDER BY `ordinal` ASC' );

            foreach ( $blog_articles_faq as $key_article_faq => $value_article_faq )
            {
                $this->getRegbyId( $value_article_faq['id'] );
    //$txt = 'Item ========== '.PHP_EOL; fwrite($this->myfile, $txt);
    //fwrite($this->myfile, print_r($this->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

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
