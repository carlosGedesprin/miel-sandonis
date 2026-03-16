<?php

namespace src\controller\views\control_panel;

use src\controller\baseViewController;
use src\util\paginator;

use \src\controller\entity\accountController;
use \src\controller\entity\userController;

class customerListViewController extends baseViewController
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
                                'company' => array(
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
    );
    private $pagination = array(
                                'num_page'       => '1',
                                'order'          => 'name',
                                'order_dir'      => 'ASC',
                                'rpp'            => '',
    );

    private $folder = 'control_panel';

    /**
     * @Route('/app/customers', name='app_customers')
     */
    public function itemslistAction()
    {
$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/cp_customerControler_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'POST =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $loadinfo = new loadInfoController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $data = $loadinfo->getLoadInfo();

        $account_connected = new accountController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $account_connected->getRegbyAccountKey( $this->account_key );
//$txt = 'Account connected =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($account_connected->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        $user_connected = new userController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $user_connected->getRegByUserKey( $this->user_key );
//$txt = 'User connected =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($account_connected->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

//$txt = 'this->list_filters =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->list_filters, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $this->list_filters['company']['caption'] = $this->lang['ACCOUNT_COMPANY'];
        $this->list_filters['company']['placeholder'] = $this->lang['ACCOUNT_COMPANY'];
        $this->list_filters['name']['caption'] = $this->lang['ACCOUNT_CUSTOMER_NAME'];
        $this->list_filters['name']['placeholder'] = $this->lang['ACCOUNT_CUSTOMER_NAME'];
        $this->list_filters['active']['caption'] = $this->lang['ACTIVE'];
        $this->list_filters['active']['placeholder'] = $this->lang['ACTIVE'];

        $this->pagination = $this->utils->request_pagination($this->pagination);
        $this->pagination['rpp'] = $this->session->config['records_per_page'];
//$txt = 'Pagination ========>'.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->pagination, TRUE));

        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );
//$txt = 'Filters from form ========>'.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->list_filters, TRUE));

        $this->list_filters['show_to_staff'] = array();
        $this->list_filters['show_to_staff']['type'] = 'hidden';
        $this->list_filters['show_to_staff']['value'] = '1'; // I'm not an admin
        $this->list_filters['show_to_staff']['value_previous'] = '1';

//$txt = 'User group ====> '.$this->group.PHP_EOL; fwrite($this->myfile, $txt);
        switch ( $this->group )
        {
            case GROUP_AGENT:
                $this->list_filters['agent'] =  array();
                $this->list_filters['agent']['type'] =  'hidden';
                $this->list_filters['agent']['value'] =  $this->account;
                $this->list_filters['agent']['value_previous'] = $this->account;
                break;
        }

$txt = 'Filters before getResultAndCount =========='.PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r($this->list_filters, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
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

        switch ( $this->group )
        {
            case GROUP_AGENT:
                unset( $this->list_filters['agent'] );
                break;
            case GROUP_INTEGRATOR:
                unset( $this->list_filters['integrator'] );
                break;
        }

        unset( $this->list_filters['show_to_staff'] );

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose($this->myfile);
        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/customers.html.twig', array(
            'res' => $res,                                  // Array with the list of records
            'filters' => $this->list_filters,               // The filters data
            'pagination' => $this->pagination,              // The pagination data
            'page_list' => $page_list,                      // Array with the list of pages numbers
            'total_pages' => $paginator->getTotalPages(),   // Total amount of pages
        ));
    }
}
