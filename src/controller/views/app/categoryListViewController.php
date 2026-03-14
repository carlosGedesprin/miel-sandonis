<?php

namespace src\controller\views\app;

use src\controller\baseViewController;
use \src\util\paginator;

class categoryListViewController extends baseViewController
{
    private $table = 'category';

    private $list_filters = array(
                                    'name' => array(
                                        'type' => 'text',
                                        'caption' => '',
                                        'placeholder' => '',
                                        'width' => '0',	// if 0 uses the rest of the row
                                        'value' => '',
                                        'value_previous' => '',
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
     * @Route('/app/categories', name='app_categories')
     */
    public function itemslistAction()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/categoryListViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'this->pagination ========>'.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->pagination, TRUE));
        $this->list_filters['name']['caption'] = $this->lang['CATEGORY_NAME'];
        $this->list_filters['name']['placeholder'] = $this->lang['CATEGORY_NAME'];

        $this->pagination = $this->utils->request_pagination($this->pagination);
        $this->pagination['rpp'] = $this->session->config['records_per_page'];

        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        list($res, $totalcount) = $this->utils->getResultAndCount( $this->table, $this->pagination, $this->list_filters );

        $paginator = new paginator($this->pagination['num_page'], $totalcount, $this->pagination['rpp']);

        $page_list = $paginator->pagesOnPagination($this->pagination['num_page']);

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//fclose($this->myfile);
        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/categories.html.twig', array(
            'res' => $res,
            'filters' => $this->list_filters,
            'pagination' => $this->pagination,
            'page_list' => $page_list,
            'total_pages' => $paginator->getTotalPages(),
        ));
    }
}
