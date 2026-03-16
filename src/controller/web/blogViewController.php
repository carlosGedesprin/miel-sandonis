<?php

namespace src\controller\web;

use \src\controller\baseViewController;

use src\controller\entity\blogCategoryController;
use src\controller\entity\blogAuthorController;
use src\controller\entity\blogArticleController;
use src\controller\entity\blogArticleLangController;
use src\controller\entity\blogArticleFAQController;

use src\controller\entity\langController;
use src\controller\entity\langNameController;

use DateTime;
use DateTimeZone;
use Exception;

class blogViewController extends baseViewController
{
    private $data = array();

    public function __construct( $args )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/blogViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        parent::__construct( $args );

        $lang = new langController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $lang_name = new langNameController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $this->data['canonical'] = '<link rel="canonical" href="'.$_ENV['protocol'].'://'.$_ENV['domain'].'%this_route%" />';

        $this->data['alternate_langs'] = '';
        $this->data['langs'] = '';
        $filter_select = array(
                                'active' => '1',
        );
        $extra_select = 'ORDER BY `code_2a`';
        $langs = $lang->getAll( $filter_select, $extra_select);
        foreach( $langs as $lang_temp_key => $lang_temp_value )
        {
            $lang->getRegbyId( $lang_temp_value['id'] );

            $this->data['alternate_langs'] .= '<link    rel="alternate"
                                                        hreflang="'.$lang->getCode2a().'"
                                                        href="'.$_ENV['protocol'].'://'.$_ENV['domain'].'%this_route%?lang='.$lang->getCode2a().'" />';

//            if ( $lang->getCode2a() != $this->session->getLanguageCode2a() )
//            {
                $lang_name->getRegbyCodeAndLang( $lang->getCode2a(), $this->session->getLanguageCode2a());
                $this->data['langs'] .= '<a href="'.$_ENV['protocol'].'://'.$_ENV['domain'].'%this_route%?lang='.$lang->getCode2a().'"
                                            class="header_contact_lang">
                                             <img src="/assets/images/web/'.$this->session->config['website_skin'].'/lang_flags/'.$lang->getCode2a().'.png" title="'.$lang_name->getName().'" />
                                         </a>';
//            }
        }
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     * @Route("blog", name="blog")
     */
    public function blogAction()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/blogViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $blog_category = new blogCategoryController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $blog_article = new blogArticleController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $blog_article_lang = new blogArticleLangController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $blog_author = new blogAuthorController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $filter_select = array(
                                'active' => '1',
        );
        $extra_select = 'ORDER BY `ordinal`';
        $categories = $blog_category->getAll( $filter_select, $extra_select);

        $filter_select = array(
                                'featured' => '1',
                                'active' => '1',
        );
//$txt = 'Articles filter select ========>' . PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($filter_select, TRUE));
        $extra_select = 'ORDER BY `category`,`ordinal` ASC';
        $articles = $blog_article->getAll( $filter_select, $extra_select);
//$txt = 'Articles from db ========>' . PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($articles, TRUE));

//$txt = 'Lang ========> '.$this->session->getLanguageCode2a().PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
        foreach( $articles as $article_temp_key => $article_temp_value )
        {
            $blog_article->getRegbyId( $article_temp_value['id'] );

            $blog_author->getRegbyId( $article_temp_value['author'] );

            $articles[$article_temp_key]['author'] = $blog_author->getName();

//$txt = 'Article to display ==> '.$blog_article->getId().' key '.$article_temp_key.PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
            $blog_article_lang->getRegbyArticleLang( $blog_article->getId(), $this->session->getLanguageCode2a() );
//$txt = 'Articles lang from db ========>' . PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($blog_article_lang->getReg(), TRUE));

            $articles[$article_temp_key]['slug'] = $blog_article_lang->getSlug();
            $articles[$article_temp_key]['title'] = $blog_article_lang->getTitle();
            $articles[$article_temp_key]['metadescription'] = $blog_article_lang->getMetadescription();
            $articles[$article_temp_key]['picture_alt_text'] = $blog_article_lang->getPictureAltText();
        }
//$txt = 'Articles to display ========>' . PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($articles, TRUE));
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
        return $this->twig->render('web/'.$this->session->config['website_skin'].'/blog/index.html.twig', array(
            'data' => $this->data,
            'categories' => $categories,
            'articles' => $articles,
        ));
    }

    /**
     * @Route("blog_slug", name="blog_slug")
     */
    public function blogSlugAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/blogViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Vars ========>' . PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($vars, TRUE));
        $blog_category = new blogCategoryController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $blog_article = new blogArticleController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $blog_article_lang = new blogArticleLangController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $vars['slug'] = rtrim( $vars['slug'], '/' );

        if ( $blog_category->getRegbySlug( $vars['slug'] ) )
        {
//$txt = 'Is a category '.$blog_category->getId().' '.$blog_category->getSlug().PHP_EOL; fwrite($this->myfile, $txt);
            echo $this->categoryAction( $vars );
            exit;
        }
        elseif ( $blog_article_lang->getRegbySlug( $vars['slug'] ) )
        {
//$txt = 'Is an article '.$blog_article->getId().' '.$blog_article->getSlug().PHP_EOL; fwrite($this->myfile, $txt);
            echo $this->articleAction( $vars );
            exit;
        }
        else
        {
//$txt = 'Not found'.PHP_EOL; fwrite($this->myfile, $txt);
            echo $this->categoriesAction();
            exit;
        }
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
    }

    /**
     * @Route("blog/categories", name="blog_categories")
     */
    public function categoriesAction()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/blogViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $blog_category = new blogCategoryController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $blog_article = new blogArticleController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $blog_article_lang = new blogArticleLangController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $blog_author = new blogAuthorController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $filter_select = array(
                                'active' => '1',
        );
        $extra_select = 'ORDER BY `ordinal`';
        $categories = $blog_category->getAll( $filter_select, $extra_select);

        $filter_select = array(
                                'featured' => '1',
                                'active' => '1',
        );
        $extra_select = 'ORDER BY `ordinal`';
        $articles = $blog_article->getAll( $filter_select, $extra_select);

        foreach( $articles as $article_temp_key => $article_temp_value )
        {
            $blog_author->getRegbyId( $article_temp_value['author'] );

            $articles[$article_temp_key]['author'] = $blog_author->getName();

            $blog_article_lang->getRegbyArticleLang( $blog_article->getId(), $this->session->getLanguageCode2a() );

            $articles[$article_temp_key]['slug'] = $blog_article_lang->getSlug();
            $articles[$article_temp_key]['title'] = $blog_article_lang->getTitle();
            $articles[$article_temp_key]['metadescription'] = $blog_article_lang->getMetadescription();
            $articles[$article_temp_key]['picture_alt_text'] = $blog_article_lang->getPictureAltText();
        }

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
        return $this->twig->render('web/'.$this->session->config['website_skin'].'/blog/categories.html.twig', array(
            'data' => $this->data,
            'categories' => $categories,
            'articles' => $articles,
        ));
    }

    /**
     * @Route("blog/category/{slug}", name="blog_category_slug")
     */
    public function categoryAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/blogViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Vars ========>' . PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($vars, TRUE));
        $blog_category = new blogCategoryController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $blog_article = new blogArticleController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $blog_article_lang = new blogArticleLangController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $blog_author = new blogAuthorController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $vars['slug'] = rtrim( $vars['slug'], '/' );

        $blog_category->getRegbySlug( $vars['slug'] );

        $filter_select = array(
                                'active' => '1',
        );
        if ( !empty ( $blog_category->getId() ) ) { $filter_select['category'] = $blog_category->getId(); }

        $extra_select = 'ORDER BY `ordinal`';
        $articles = $blog_article->getAll( $filter_select, $extra_select );

        foreach( $articles as $article_temp_key => $article_temp_value )
        {
            $blog_author->getRegbyId( $article_temp_value['author'] );

            $articles[$article_temp_key]['author'] = $blog_author->getName();

            $blog_article_lang->getRegbyArticleLang( $blog_article->getId(), $this->session->getLanguageCode2a() );

            $articles[$article_temp_key]['slug'] = $blog_article_lang->getSlug();
            $articles[$article_temp_key]['title'] = $blog_article_lang->getTitle();
            $articles[$article_temp_key]['metadescription'] = $blog_article_lang->getMetadescription();
            $articles[$article_temp_key]['picture_alt_text'] = $blog_article_lang->getPictureAltText();
        }
//$txt = 'Articles ========>' . PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($articles, TRUE));

        $filter_select = array(
                                'active' => '1',
        );
        $extra_select = 'ORDER BY `ordinal`';
        $categories = $blog_category->getAll( $filter_select, $extra_select );

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose($this->myfile);
        return $this->twig->render('web/'.$this->session->config['website_skin'].'/blog/category.html.twig', array(
            'data' => $this->data,
            'category' => $blog_category->getReg(),
            'categories' => $categories,
            'articles' => $articles,
        ));
    }

    /**
     * @Route("/blog/articles/{category_key}", name="blog_articles_key")
     */
    public function articlesAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/blogViewController_'.__FUNCTION__.'.txt', 'a+') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Vars ========>' . PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($vars, TRUE));
        $blog_article = new blogArticleController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $blog_article_lang = new blogArticleLangController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $blog_category = new blogCategoryController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $blog_author = new blogAuthorController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        if ( isset( $vars['category'] ) && !empty( $vars['category'] ) )
        {
//$txt = 'Category slug '.$vars['category'].PHP_EOL; fwrite($this->myfile, $txt);
            $blog_category->getRegbySlug( $vars['category'] );
//$txt = 'Category '.$blog_category->getId().PHP_EOL; fwrite($this->myfile, $txt);
        }

        $filter_select = array(
                                'active' => '1',
        );
        if ( !empty ( $blog_category->getId() ) ) { $filter_select['category'] = $blog_category->getId(); }
//$txt = 'Articles filter select========>' . PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($filter_select, TRUE));

        $extra_select = 'ORDER BY `ordinal`';
        $articles = $blog_article->getAll( $filter_select, $extra_select);
//$txt = 'Articles from db ========>' . PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($articles, TRUE));

        foreach( $articles as $article_temp_key => $article_temp_value )
        {
            $blog_article->getRegbyId( $article_temp_value['id'] );

            $articles[$article_temp_key] = $blog_article->getReg();

            $blog_author->getRegbyId( $blog_article->getAuthor() );

            $articles[$article_temp_key]['author'] = $blog_author->getName();

            $blog_article_lang->getRegbyArticleLang( $blog_article->getId(), $this->session->getLanguageCode2a() );

            $articles[$article_temp_key]['slug'] = $blog_article_lang->getSlug();
            $articles[$article_temp_key]['title'] = $blog_article_lang->getTitle();
            $articles[$article_temp_key]['metadescription'] = $blog_article_lang->getMetadescription();
            $articles[$article_temp_key]['picture_alt_text'] = $blog_article_lang->getPictureAltText();
        }
//$txt = 'Articles to display ========>' . PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($articles, TRUE));
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose($this->myfile);
        return $this->twig->render('web/'.$this->session->config['website_skin'].'/blog/articles.html.twig', array(
            'data' => $this->data,
            'category' => $blog_category->getId(),
            'articles' => $articles,
        ));
    }

    /**
     * @Route("/blog/article/{key}", name="blog_article_key")
     */
    public function articleAction( $vars )
    {
$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/blogViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
        $blog_article = new blogArticleController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $blog_article_lang = new blogArticleLangController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $blog_article_langs = new blogArticleLangController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $blog_article_faq = new blogArticleFaqController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $blog_category = new blogCategoryController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $blog_author = new blogAuthorController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $lang = new langController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $lang_name = new langNameController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

$txt = 'URL ========> '.$this->startup->getURL().PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
        $filter_select = array(
                                'active' => '1',
        );

        $extra_select = 'ORDER BY `ordinal`';
        $blog_categories = $blog_category->getAll( $filter_select, $extra_select);
//$txt = 'Categories ========>'.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($blog_categories, TRUE));

        $blog_article_faqs = array();

//$txt = 'Slug ========> '.$vars['slug'].PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
        if ( $blog_article_lang->getRegbySlug( $vars['slug'] ) )
        {
//$txt = 'Blog article lang ========> '.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($blog_article_lang->getReg(), TRUE));
            $blog_article->getRegbyId( $blog_article_lang->getArticle() );
//$txt = 'Blog article ========> '.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($blog_article->getReg(), TRUE));

            $blog_article->setDate( ( $blog_article->getDate() == '' )? NULL : $blog_article->getDate()->format('d-m-Y') );
//$txt = 'Blog article date ========> '.$blog_article->getDate().PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);

            $blog_author->getRegbyId( $blog_article->getAuthor() );
//$txt = 'Blog article author ========> '.$blog_author->getname().PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);

            $blog_category->getRegbyId( $blog_article->getCategory() );
//$txt = 'Blog article category ========> '.$blog_category->getTitle().PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);

            $blog_article_lang->getRegbyArticleLang( $blog_article->getId(), $this->session->getLanguageCode2a() );
            $blog_article_lang->setText( html_entity_decode( $blog_article_lang->getText() ) );

            $filter_select = array(
                                    'article' => $blog_article->getId(),
                                    'lang_code_2a' => $this->session->getLanguageCode2a(),
                                    'active' => '1',
            );

            $extra_select = ' AND `question` is NOT NULL ORDER BY `ordinal`';
            $blog_article_faqs = $blog_article_faq->getAll( $filter_select, $extra_select);
//$txt = 'Article FAQ ========> '.$blog_article->getId().PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($blog_article_faqs, TRUE));

            $this->data['alternate_langs'] = '';
            $this->data['langs'] = '';
            $filter_select = array(
                                    'active' => '1',
            );
            $extra_select = 'ORDER BY `code_2a`';
            $langs = $lang->getAll( $filter_select, $extra_select);
            foreach( $langs as $lang_temp_key => $lang_temp_value )
            {
                $lang->getRegbyId( $lang_temp_value['id'] );

                $blog_article_langs->getRegbyArticleLang( $blog_article->getId(), $lang->getCode2a() );

                $this->data['alternate_langs'] .= '<link    rel="alternate"
                                                        hreflang="'.$lang->getCode2a().'"
                                                        href="'.$_ENV['protocol'].'://'.$_ENV['domain'].'/blog/'.$blog_article_langs->getSlug().'?lang='.$lang->getCode2a().'" />';

//                if ( $lang->getCode2a() != $this->session->getLanguageCode2a() )
//                {
                    $lang_name->getRegbyCodeAndLang( $lang->getCode2a(), $this->session->getLanguageCode2a());
                    $this->data['langs'] .= '<a href="'.$_ENV['protocol'].'://'.$_ENV['domain'].'/blog/'.$blog_article_langs->getSlug().'?lang='.$lang->getCode2a().'"
                                            class="header_contact_lang">
                                             <img src="/assets/images/web/'.$this->session->config['website_skin'].'/lang_flags/'.$lang->getCode2a().'.png" title="'.$lang_name->getName().'" />
                                         </a>';
//                }
            }

        }
        else
        {
//$txt = 'Not found'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Article ========>' . PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($blog_article->getReg(), TRUE));
            echo $this->articlesAction( [] );
        }

$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
fclose($this->myfile);
        return $this->twig->render('web/'.$this->session->config['website_skin'].'/blog/article.html.twig', array(
            'data' => $this->data,
            'article' => $blog_article->getReg(),
            'article_lang' => $blog_article_lang->getReg(),
            'author' => $blog_author->getReg(),
            'article_faqs' => $blog_article_faqs,
            'categories' => $blog_categories,
        ));
    }
    /**
     * @Route("/sitemap_index.xml", name="sitemap_index")
     */
    public function sitemapAction()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/sitemapController'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//http://www.danielazucotti.com/conocimientos/generar-sitemap-xml-con-php-de-forma-dinamica/
        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']) );

        $xml = '<?xml version="1.0" encoding="UTF-8"?>
                    <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
/*
        $blog_category = new blogCategoryController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $rows = $blog_category->getAll( ['active' => '1'], NULL );

        foreach ( $rows as $row )
        {
            $loc = $_ENV['protocol'].'://'.$_ENV['domain'].'/blog/'.$row['slug'];

            $xml .= '<url>
                <loc>'.$loc.'</loc>
                <lastmod>2024-04-16 10:52 +00:00</lastmod>
                <changefreq>monthly</changefreq>
                <priority>0.9</priority>
            </url>';
        }
*/
        $blog_article = new blogArticleController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $blog_article_lang = new blogArticleLangController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $filter_select = array(
                                'active' => '1',
        );

        $extra_select = 'ORDER BY `ordinal`';
        $rows = $blog_article->getAll( $filter_select, $extra_select );

        foreach ( $rows as $row )
        {
            $date = ( !empty($row['date']) )? DateTime::createFromFormat('Y-m-d', $row['date'], new DateTimeZone($this->session->config['time_zone'])) : $now;

            $blog_article_lang->getRegbyArticleLang( $row['id'], $this->session->getLanguageCode2a() );

            $loc = $_ENV['protocol'].'://'.$_ENV['domain'].'/blog/'.$blog_article_lang->getSlug();

            $xml .= '<url>
                <loc>'.$loc.'</loc>
                <lastmod>'.$date->format('Y-m-d').'</lastmod>
                <changefreq>weekly</changefreq>
                <priority>0.8</priority>
            </url>';
        }

        $xml .='</urlset>';

        header('Content-type:text/xml;charset:utf8');
        echo $xml;

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
    }
}