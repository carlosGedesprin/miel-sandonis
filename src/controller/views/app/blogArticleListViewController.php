<?php

namespace src\controller\views\app;

use src\controller\baseViewController;

use \src\controller\entity\blogArticleLangController;
use \src\controller\entity\blogCategoryController;

use \src\util\paginator;

class blogArticleListViewController extends baseViewController
{
    private $table = 'blog_article';

    private $list_filters = array(
                                    'title' => array(
                                        'type' => 'text',
                                        'caption' => '',
                                        'placeholder' => '',
                                        'width' => '0',	// if 0 uses the rest of the row
                                        'value' => '',
                                        'value_previous' => '',
                                    ),
                                    'category' => array(
                                        'type' => 'select',
                                        'caption' => '',
                                        'placeholder' => '',
                                        'width' => '0',	// if 0 uses the rest of the row
                                        'value' => '',
                                        'value_previous' => '',
                                        'chain_childs' => '',
                                        'options' => '',
                                    ),
    );
    private $pagination = array(
                                'num_page'       => '1',
                                'order'          => 'id',
                                'order_dir'      => 'ASC',
                                'rpp'            => '',
    );

    private $folder = 'app';

    /**
     * @Route('/app/blog_articles', name='app_blog_articles')
     */
    public function itemslistAction()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/blogArticleListViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'this->pagination ========>'.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->pagination, TRUE));
        $this->list_filters['title']['caption'] = $this->lang['BLOG_ARTICLE_TITLE'];
        $this->list_filters['title']['placeholder'] = $this->lang['BLOG_ARTICLE_TITLE'];
        $this->list_filters['category']['caption'] = $this->lang['BLOG_ARTICLE_CATEGORY'];
        $this->list_filters['category']['placeholder'] = $this->lang['BLOG_ARTICLE_CATEGORY'];

        $this->pagination = $this->utils->request_pagination($this->pagination);
        $this->pagination['rpp'] = $this->session->config['records_per_page'];

        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        list($res, $totalcount) = $this->utils->getResultAndCount( $this->table, $this->pagination, $this->list_filters );

        $paginator = new paginator($this->pagination['num_page'], $totalcount, $this->pagination['rpp']);

        $page_list = $paginator->pagesOnPagination($this->pagination['num_page']);

        $blog_article_lang = new blogArticleLangController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        foreach ( $res as $res_temp_key => $res_temp_value )
        {
            $blog_article_lang->getRegbyArticleLang( $res_temp_value['id'], $this->session->getLanguageCode2a() );

            $res[$res_temp_key]['title'] = $blog_article_lang->getTitle();
        }

        // Filter category select options list
        $filter_options = '';
        $filter_options .= '<option value=""'.(($this->list_filters['category']['value'] == '')? ' selected="selected" ' : '').'>'.(($this->list_filters['category']['value'] == '')? $this->lang['BLOG_CATEGORY_SELECT'] : $this->lang['BLOG_CATEGORY_ALL']).'</option>';
        $filter_options .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        $filter_select = null;
        $extra_select = 'ORDER BY `title`';
        $blog_category = new blogCategoryController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $rows = $blog_category->getAll( $filter_select, $extra_select );
        foreach ( $rows as $row)
        {
            $filter_options .= '<option value="' . $row['id'] . '"' . (($this->list_filters['category']['value'] == $row['id']) ? ' selected="selected" ' : '') . '>' . $row['title'] . '</option>';
        }
        $this->list_filters['category']['options'] = $filter_options;
        unset($filter_options);

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose($this->myfile);
        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/blog_articles.html.twig', array(
            'res' => $res,                                  // Array with the list of records
            'filters' => $this->list_filters,               // The filters data
            'pagination' => $this->pagination,              // The pagination data
            'page_list' => $page_list,                      // Array with the list of pages numbers
            'total_pages' => $paginator->getTotalPages(),   // Total amount of pages
        ));
    }
}
