<?php

namespace src\controller\views\app;

use src\controller\baseViewController;

use \src\controller\entity\accountController;

use src\util\paginator;

class userListViewController extends baseViewController
{
    private $table = 'user';

    private $list_filters = array(
                                'name' => array(
													'type' => 'text',
													'caption' => '',
													'placeholder' => '',
													'width' => '0',	// if 0 uses the rest of the row
													'value' => '',
													'value_previous' => '',
                                ),
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
     * @Route('/app/users', name='app_users')
     */
    public function itemslistAction()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/userListViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'POST =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        $this->list_filters['name']['caption'] = $this->lang['USER'];
        $this->list_filters['name']['placeholder'] = $this->lang['USER_NAME'];
        $this->list_filters['account']['caption'] = $this->lang['ACCOUNT'];
        $this->list_filters['account']['placeholder'] = $this->lang['ACCOUNT'];
        $this->list_filters['active']['caption'] = $this->lang['USER_ACTIVE'];
        $this->list_filters['active']['placeholder'] = $this->lang['USER_ACTIVE'];
//$txt = 'this->list_filters =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->list_filters, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $this->pagination = $this->utils->request_pagination($this->pagination);
//$txt = 'this->pagination =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->pagination, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        $this->pagination['rpp'] = $this->session->config['records_per_page'];

        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

//$txt = 'this->group ========== ('.$this->group.')'.PHP_EOL; fwrite($this->myfile, $txt);
        if ( in_array($this->group, [GROUP_SUPER_ADMIN, GROUP_ADMIN]) )
        {
            unset($this->list_filters['show_to_staff']);
        }
        else
        {
            $this->list_filters['show_to_staff']['value'] = $this->list_filters['show_to_staff']['value_previous'] = '1';
        }

        list($res, $totalcount) = $this->utils->getResultAndCount( $this->table, $this->pagination, $this->list_filters );

        $paginator = new paginator($this->pagination['num_page'], $totalcount, $this->pagination['rpp']);

        $page_list = $paginator->pagesOnPagination($this->pagination['num_page']);

        // Filter account select options list
        $filter_options = '';
        $filter_options .= '<option value=""'.(($this->list_filters['account']['value'] == '')? ' selected="selected" ' : '').'>'.(($this->list_filters['account']['value'] == '0')? $this->lang['ACCOUNT_SELECT'] : $this->lang['ACCOUNT_ALL']).'</option>';
        $filter_options .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        $filter_select = NULL;
        if ( $this->group != GROUP_SUPER_ADMIN && $this->group != GROUP_ADMIN ) {
            $filter_select = array('show_to_staff' => '1');
        }
        $extra_select = 'ORDER BY `name`';
        $account = new accountController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $rows = $account->getAll( $filter_select, $extra_select);
        foreach ( $rows as $row)
        {
            $filter_options .= '<option value="' . $row['id'] . '"' . (($this->list_filters['account']['value'] == $row['id']) ? ' selected="selected" ' : '') . '>'.$row['name'].' - '.$row['id'].'</option>';
        }
        $this->list_filters['account']['options'] = $filter_options;
        unset($filter_options);

        // Filter active select options list
        $filter_options = '';
        $filter_options .= '<option value=""'.(($this->list_filters['active']['value'] == '')? ' selected="selected" ' : '').'>'.(($this->list_filters['active']['value'] == '')? $this->lang['ACTIVE_SELECT'] : $this->lang['ALL']).'</option>';
        $filter_options .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        $filter_options .= '<option value="1"' . (($this->list_filters['active']['value'] == '1') ? ' selected="selected" ' : '') . '>' . $this->lang['YES'] . '</option>';
        $filter_options .= '<option value="0"' . (($this->list_filters['active']['value'] == '0') ? ' selected="selected" ' : '') . '>' . $this->lang['NO'] . '</option>';
        $this->list_filters['active']['options'] = $filter_options;
        unset($filter_options);

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose($this->myfile);
        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/users.html.twig', array(
            'res' => $res,                                  // Array with the list of records
            'filters' => $this->list_filters,               // The filters data
            'pagination' => $this->pagination,              // The pagination data
            'page_list' => $page_list,                      // Array with the list of pages numbers
            'total_pages' => $paginator->getTotalPages(),   // Total amount of pages
        ));
    }
}
