<?php

namespace  src\controller\views\app;

use src\controller\baseViewController;

use src\controller\entity\accountController;
use src\controller\entity\accountFundsController;

use src\util\paginator;

class accountFundsListViewController extends baseViewController
{
    private $table = 'account_funds';

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
    );
    private $pagination = array(
                                'num_page'       => '1',
                                'order'          => 'id',
                                'order_dir'      => 'DESC',
                                'rpp'            => '',
    );

    private $folder = 'app';

    /**
     *
     * @Route('/app/account_s', name='app_account')
     *
     */
    public function itemslistAction()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/accountListViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = 'accountListViewController '.__FUNCTION__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'POST =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        $this->list_filters['account']['caption'] = $this->lang['ACCOUNT'];
        $this->list_filters['account']['placeholder'] = $this->lang['ACCOUNT'];

        $this->pagination = $this->utils->request_pagination($this->pagination);
        $this->pagination['rpp'] = $this->session->config['records_per_page'];

        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

//fwrite($this->myfile, $txt);
//$txt = 'Filters =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->list_filters, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        list($res, $totalcount) = $this->utils->getResultAndCount( $this->table, $this->pagination, $this->list_filters );

        $paginator = new paginator($this->pagination['num_page'], $totalcount, $this->pagination['rpp']);

        $page_list = $paginator->pagesOnPagination($this->pagination['num_page']);

        // Filter account select options list
        $filter_options = '';
        $filter_options .= '<option value=""'.(($this->list_filters['account']['value'] == '')? ' selected="selected" ' : '').'>'.(($this->list_filters['account']['value'] == '0')? $this->lang['ACCOUNT_SELECT'] : $this->lang['ACCOUNT_ALL']).'</option>';
        $filter_options .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        $filter_select = array(
            'show_to_staff' => '1'
        );
        $extra_select = 'ORDER BY `name`';
        $account = new accountController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $rows = $account->getAll( $filter_select, $extra_select);
        foreach ( $rows as $row)
        {
            $filter_options .= '<option value="' . $row['id'] . '"' . (($this->list_filters['account']['value'] == $row['id']) ? ' selected="selected" ' : '') . '>'.$row['name'].' - '.$row['id'].'</option>';
        }
        $this->list_filters['account']['options'] = $filter_options;
        unset($filter_options);

        $account_balance = new accountFundsController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $balance = ( $this->list_filters['account']['value'] == '' )? $account_balance->getGeneralBalance() : $account_balance->getBalancebyAccount( $this->list_filters['account']['value'] );

        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/accountFunds.html.twig', array(
            'res' => $res,                                  // Array with the list of records
            'filters' => $this->list_filters,               // The filters data
            'pagination' => $this->pagination,              // The pagination data
            'page_list' => $page_list,                      // Array with the list of pages numbers
            'total_pages' => $paginator->getTotalPages(),   // Total amount of pages
            'balance' => $balance,
        ));
    }
}
