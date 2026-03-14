<?php

namespace src\controller\views\app;

use src\controller\baseViewController;

use \src\controller\entity\productTypeController;

use src\util\paginator;

class productListViewController extends baseViewController
{
    private $table = 'product';

    private $list_filters = array(
                                'name' => array(
													'type' => 'text',
													'caption' => '',
													'placeholder' => '',
													'width' => '0',	// if 0 uses the rest of the row
													'value' => '',
													'value_previous' => '',
                                ),
                                'product_type' => array(
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
     * @Route('/app/products', name='app_products')
     */
    public function itemslistAction()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/productListViewController_'.__FUNCTION__.'.txt', 'a+') or die('Unable to open file!');
//$txt = 'productListViewController '.__FUNCTION__.' start ==============================================================='.PHP_EOL;
//$txt = 'this->pagination ========>'.PHP_EOL;fwrite($this->myfile, $txt);

//fwrite($this->myfile, print_r($this->pagination, TRUE));
//$txt = 'productListViewController '.__FUNCTION__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//fclose($this->myfile);
        $this->list_filters['name']['caption'] = $this->lang['PRODUCT_NAME'];
        $this->list_filters['name']['placeholder'] = $this->lang['PRODUCT_NAME'];
        $this->list_filters['product_type']['caption'] = $this->lang['PRODUCT_TYPE'];
        $this->list_filters['product_type']['placeholder'] = $this->lang['PRODUCT_TYPE_SELECT']; 

        $this->pagination = $this->utils->request_pagination($this->pagination);
        $this->pagination['rpp'] = $this->session->config['records_per_page'];

        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        list($res, $totalcount) = $this->utils->getResultAndCount( $this->table, $this->pagination, $this->list_filters );

        $paginator = new paginator($this->pagination['num_page'], $totalcount, $this->pagination['rpp']);

        $page_list = $paginator->pagesOnPagination($this->pagination['num_page']);

        // Filter product type select options list
        $filter_options = '';
        //$filter_options .= '<option value=""'.(($this->list_filters['product_type']['value'] == '')? ' selected="selected" ' : '').'>'.(($this->list_filters['product_type']['value'] == '')? $this->lang['PRODUCT_TYPE_SELECT'] : $this->lang['PRODUCT_TYPE_ALL']).'</option>';   
        $filter_options .= '<option value=""'.(($this->list_filters['product_type']['value'] == '')? ' selected="selected" ' : '').'>'.(($this->list_filters['product_type']['value'] == '')? $this->lang['PRODUCT_TYPE_SELECT'] : $this->lang['PRODUCT_TYPE_ALL']).'</option>';
        $filter_options .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        $filter_select = NULL;
        $extra_select = 'ORDER BY `name`';
        $product_type = new productTypeController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $rows = $product_type->getAll( $filter_select, $extra_select);
        foreach ( $rows as $row)
        {
            $filter_options .= '<option value="' . $row['id'] . '"' . (($this->list_filters['product_type']['value'] == $row['id']) ? ' selected="selected" ' : '') . '>' . $row['name'] . '</option>';
        }
        $this->list_filters['product_type']['options'] = $filter_options;
        unset($filter_options);

        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/products.html.twig', array(
            'res' => $res,                                  // Array with the list of records
            'filters' => $this->list_filters,               // The filters data
            'pagination' => $this->pagination,              // The pagination data
            'page_list' => $page_list,                      // Array with the list of pages numbers
            'total_pages' => $paginator->getTotalPages(),   // Total amount of pages
        ));
    }
}
