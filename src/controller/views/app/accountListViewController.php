<?php

namespace  src\controller\views\app;

use src\controller\baseViewController;

use \src\controller\entity\accountController;

use src\util\paginator;

class accountListViewController extends baseViewController
{
    private $table = 'account';

    private $list_filters = array(
                                'name' => array(
													'type' => 'text',
													'caption' => '',
													'placeholder' => '',
													'width' => '0',	// if 0 uses the rest of the row
													'value' => '',
													'value_previous' => '',
                                ),
                                'active' => array(
                                                    'type' => 'select',
                                                    'caption' => '',
                                                    'placeholder' => '',
                                                    'width' => '0',	// if 0 uses the rest of the row
                                                    'value' => '',
                                                    'value_previous' => '',
                                                    'chain_childs' => '',
                                                    'options' => '',
                                ),
                                'show_to_staff' => array(
                                                    'type' => 'hidden',
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
     *
     * @Route('/app/accounts', name='app_account')
     *
     */
    public function itemslistAction()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/accountListViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = 'accountListViewController '.__FUNCTION__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'POST =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        $this->list_filters['name']['caption'] = $this->lang['ACCOUNT_NAME'];
        $this->list_filters['name']['placeholder'] = $this->lang['ACCOUNT_NAME'];
        $this->list_filters['active']['caption'] = $this->lang['ACCOUNT_ACTIVE'];
        $this->list_filters['active']['placeholder'] = $this->lang['ACCOUNT_ACTIVE'];

        $this->pagination = $this->utils->request_pagination($this->pagination);
        $this->pagination['rpp'] = $this->session->config['records_per_page'];

        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

//$txt = 'this->group ========== ('.$this->group.')';
        if ( in_array($this->group, [GROUP_SUPER_ADMIN, GROUP_ADMIN]) )
        {
            unset($this->list_filters['show_to_staff']);
        }
        else
        {
            $this->list_filters['show_to_staff']['value'] = $this->list_filters['show_to_staff']['value_previous'] = '1';
        }
//fwrite($this->myfile, $txt);
//$txt = 'Filters =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->list_filters, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        list($res, $totalcount) = $this->utils->getResultAndCount( $this->table, $this->pagination, $this->list_filters );

        $paginator = new paginator($this->pagination['num_page'], $totalcount, $this->pagination['rpp']);

        $page_list = $paginator->pagesOnPagination($this->pagination['num_page']);

        // Filter active select options list
        $filter_options = '';
        $filter_options .= '<option value=""'.(($this->list_filters['active']['value'] === '')? ' selected="selected" ' : '').'>'.(($this->list_filters['active']['value'] === '')? $this->lang['ACTIVE_SELECT'] : $this->lang['ALL']).'</option>';
        $filter_options .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        $filter_options .= '<option value="1"' . (($this->list_filters['active']['value'] == '1') ? ' selected="selected" ' : '') . '>' . $this->lang['YES'] . '</option>';
        $filter_options .= '<option value="0"' . (($this->list_filters['active']['value'] == '0') ? ' selected="selected" ' : '') . '>' . $this->lang['NO'] . '</option>';
        $this->list_filters['active']['options'] = $filter_options;
        unset($filter_options);

        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/accounts.html.twig', array(
            'res' => $res,                                  // Array with the list of records
            'filters' => $this->list_filters,               // The filters data
            'pagination' => $this->pagination,              // The pagination data
            'page_list' => $page_list,                      // Array with the list of pages numbers
            'total_pages' => $paginator->getTotalPages(),   // Total amount of pages
        ));
    }
}
