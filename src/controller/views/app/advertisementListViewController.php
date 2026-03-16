<?php

namespace src\controller\views\app;

use src\controller\baseViewController;
use src\util\paginator;

use src\controller\entity\accountController;
use src\controller\entity\categoryController;


class advertisementListViewController extends baseViewController
{
    private $table = 'advertisement';

    private $list_filters = array(
                                    'account' => array(
                                        'type' => 'select',
                                        'caption' => '',
                                        'placeholder' => '',
                                        'width' => '0',	// if 0 uses the rest of the row
                                        'value' => '',
                                        'value_previous' => '',
                                        'chain_childs' => '',
                                        'options' => '',
                                    ),
                                    'billing_account' => array(
                                        'type' => 'select',
                                        'caption' => '',
                                        'placeholder' => '',
                                        'width' => '0',	// if 0 uses the rest of the row
                                        'value' => '',
                                        'value_previous' => '',
                                        'chain_childs' => '',
                                        'options' => '',
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
    );
    private $pagination = array(
                                'num_page'       => '1',
                                'order'          => 'id',
                                'order_dir'      => 'ASC',
                                'rpp'            => '',
    );

    private $folder = 'app';

    /**
     * @Route('/app/advertisments', name='app_advertisments')
     */
    public function itemslistAction()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/advertismentListViewController_'.__FUNCTION__.'.txt', 'a+') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//$txt = 'this->pagination ========>'.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->pagination, TRUE));

        $this->list_filters['account']['caption'] = $this->lang['ADVERTISEMENT_ACCOUNT'];
        $this->list_filters['account']['placeholder'] = $this->lang['ADVERTISEMENT_ACCOUNT'];
        $this->list_filters['billing_account']['caption'] = $this->lang['ADVERTISEMENT_BILLING_ACCOUNT'];
        $this->list_filters['billing_account']['placeholder'] = $this->lang['ADVERTISEMENT_BILLING_ACCOUNT'];
        $this->list_filters['category']['caption'] = $this->lang['ADVERTISEMENT_CATEGORY'];
        $this->list_filters['category']['placeholder'] = $this->lang['ADVERTISEMENT_CATEGORY'];
        $this->list_filters['active']['caption'] = $this->lang['ADVERTISEMENT_ACTIVE'];
        $this->list_filters['active']['placeholder'] = $this->lang['ADVERTISEMENT_ACTIVE'];

        $this->pagination = $this->utils->request_pagination($this->pagination);
        $this->pagination['rpp'] = $this->session->config['records_per_page'];

        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        list($res, $totalcount) = $this->utils->getResultAndCount( $this->table, $this->pagination, $this->list_filters );

        $paginator = new paginator($this->pagination['num_page'], $totalcount, $this->pagination['rpp']);

        $page_list = $paginator->pagesOnPagination($this->pagination['num_page']);

        // Filter account select options list
        $filter_options = '';
        $filter_options .= '<option value=""'.(($this->list_filters['account']['value'] == '')? ' selected="selected" ' : '').'>'.(($this->list_filters['account']['value'] == '')? $this->lang['ACCOUNT_SELECT'] : $this->lang['ACCOUNT_ALL']).'</option>';
        $filter_options .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        $filter_select = NULL;
        $extra_select = 'ORDER BY `name`';
        if ( $this->group != GROUP_SUPER_ADMIN && $this->group != GROUP_ADMIN ) {
            $filter_select = array('show_to_staff' => '1');
        }
        $account = new accountController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $rows = $account->getAll( $filter_select, $extra_select);
        foreach ( $rows as $row )
        {
            $filter_options .= '<option value="' . $row['id'] . '"' . (($this->list_filters['account']['value'] == $row['id']) ? ' selected="selected" ' : '') . '>'.$row['name'].' - '.$row['id'].' - '.$this->utils->getGroupName( $row['group']) . '</option>';
        }
        $this->list_filters['account']['options'] = $filter_options;
        unset($filter_options);

        // Filter billing_account select options list
        $filter_options = '';
        $filter_options .= '<option value=""'.(($this->list_filters['billing_account']['value'] == '')? ' selected="selected" ' : '').'>'.(($this->list_filters['billing_account']['value'] == '')? $this->lang['BILLING_ACCOUNT_SELECT'] : $this->lang['BILLING_ACCOUNT_ALL']).'</option>';
        $filter_options .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        $filter_select = NULL;
        $extra_select = 'ORDER BY `name`';
        if ( $this->group != GROUP_SUPER_ADMIN && $this->group != GROUP_ADMIN ) {
            $filter_select = array('show_to_staff' => '1');
        }
        $account = new accountController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $rows = $account->getAll( $filter_select, $extra_select);
        foreach ( $rows as $row )
        {
            $filter_options .= '<option value="' . $row['id'] . '"' . (($this->list_filters['billing_account']['value'] == $row['id']) ? ' selected="selected" ' : '') . '>'.$row['name'].' - '.$row['id'].' - '.$this->utils->getGroupName( $row['group']) . '</option>';
        }
        $this->list_filters['billing_account']['options'] = $filter_options;
        unset($filter_options);

        // Filter category select options list
        $filter_options = '';
        $filter_options .= '<option value=""'.(($this->list_filters['category']['value'] == '')? ' selected="selected" ' : '').'>'.(($this->list_filters['category']['value'] == '')? $this->lang['CATEGORY_SELECT'] : $this->lang['CATEGORY_ALL']).'</option>';
        $filter_options .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        $filter_select = NULL;
        $extra_select = 'ORDER BY `name`';
        if ( $this->group != GROUP_SUPER_ADMIN && $this->group != GROUP_ADMIN ) {
            $filter_select = array('show_to_staff' => '1');
        }
        $category = new categoryController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $rows = $category->getAll( $filter_select, $extra_select);
        foreach ( $rows as $row )
        {
            $filter_options .= '<option value="' . $row['id'] . '"' . (($this->list_filters['category']['value'] == $row['id']) ? ' selected="selected" ' : '') . '>'.$row['name'].' - '.$row['id'] . '</option>';
        }
        $this->list_filters['category']['options'] = $filter_options;
        unset($filter_options);

        //Filter active select options list
        $filter_options = '';
        $filter_options .= '<option value=""'.(($this->list_filters['active']['value'] === '')? ' selected="selected" ' : '').'>'.(($this->list_filters['active']['value'] === '')? $this->lang['ACTIVE_SELECT'] : $this->lang['ALL']).'</option>';
        $filter_options .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        $filter_options .= '<option value="1"' . (($this->list_filters['active']['value'] == '1') ? ' selected="selected" ' : '') . '>' . $this->lang['YES'] . '</option>';
        $filter_options .= '<option value="0"' . (($this->list_filters['active']['value'] == '0') ? ' selected="selected" ' : '') . '>' . $this->lang['NO'] . '</option>';
        $this->list_filters['active']['options'] = $filter_options;
        unset($filter_options);

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose($this->myfile);
        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/advertisements.html.twig', array(
            'res' => $res,                                  // Array with the list of records
            'filters' => $this->list_filters,               // The filters data
            'pagination' => $this->pagination,              // The pagination data
            'page_list' => $page_list,                      // Array with the list of pages numbers
            'total_pages' => $paginator->getTotalPages(),   // Total amount of pages
        ));
    }
}
