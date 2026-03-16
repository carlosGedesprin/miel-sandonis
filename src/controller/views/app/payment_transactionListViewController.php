<?php

namespace src\controller\views\app;

use src\controller\baseViewController;
use \src\util\paginator;

class payment_transactionListViewController extends baseViewController
{
    private $table = 'payment_transaction';

    private $list_filters = array(
                                    'transaction_key' => array(
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
    );
    private $pagination = array(
                                'num_page'       => '1',
                                'order'          => 'date_reg',
                                'order_dir'      => 'ASC',
                                'rpp'            => '',
    );

    private $folder = 'app';

    /**
     * @Route('/app/payment_transactions', name='app_payment_transactions')
     */
    public function itemslistAction()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/botListViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = 'botListViewController '.__FUNCTION__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'this->pagination ========>'.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->pagination, TRUE));
//$txt = 'botListViewController '.__FUNCTION__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose($this->myfile);
        $this->list_filters['account']['caption'] = $this->lang['PAYMENT_TRANSACTION_ACCOUNT'];
        $this->list_filters['account']['placeholder'] = $this->lang['PAYMENT_TRANSACTION_ACCOUNT'];
        $this->list_filters['transaction_key']['caption'] = $this->lang['PAYMENT_TRANSACTION_KEY'];
        $this->list_filters['transaction_key']['placeholder'] = $this->lang['PAYMENT_TRANSACTION_KEY'];

        $this->pagination = $this->utils->request_pagination($this->pagination);
        $this->pagination['rpp'] = $this->session->config['records_per_page'];

        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        list($res, $totalcount) = $this->utils->getResultAndCount( $this->table, $this->pagination, $this->list_filters );

        $paginator = new paginator($this->pagination['num_page'], $totalcount, $this->pagination['rpp']);

        $page_list = $paginator->pagesOnPagination($this->pagination['num_page']);

        // Filter account select options list
        $filter_options = '';
        $filter_options .= '<option value=""'.(($this->list_filters['account']['value'] == '')? ' selected="selected" ' : '').'>'.(($this->list_filters['account']['value'] == '0')? $this->lang['ACCOUNT_SELECT'] : $this->lang['ACCOUNT_ALL']).'</option>';
        $filter_options .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        $extra_filter = NULL;
        if ( $this->group != GROUP_SUPER_ADMIN && $this->group != GROUP_ADMIN ) {
            $extra_filter = array('show_to_staff' => '1');
        }
        $rows = $this->db->fetchAll('account', 'id, name', $extra_filter, 'ORDER BY `name`');
        foreach ( $rows as $row)
        {
            $filter_options .= '<option value="' . $row['id'] . '"' . (($this->list_filters['account']['value'] == $row['id']) ? ' selected="selected" ' : '') . '>' . $row['name'] . '</option>';
        }
        $this->list_filters['account']['options'] = $filter_options;
        unset($filter_options);

        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/payment_transactions.html.twig', array(
            'res' => $res,                                  // Array with the list of records
            'filters' => $this->list_filters,               // The filters data
            'pagination' => $this->pagination,              // The pagination data
            'page_list' => $page_list,                      // Array with the list of pages numbers
            'total_pages' => $paginator->getTotalPages(),   // Total amount of pages
        ));
    }
}
