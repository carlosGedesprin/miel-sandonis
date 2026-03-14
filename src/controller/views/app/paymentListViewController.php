<?php

namespace src\controller\views\app;

use src\controller\baseViewController;
use src\util\paginator;

use \src\controller\entity\paymentTypeController;

class paymentListViewController extends baseViewController
{
    private $table = 'payment';

    private $list_filters = array(
                                'payment_type' => array(
													'type' => 'select',
													'caption' => '',
													'placeholder' => '',
													'width' => '0',	// if 0 uses the rest of the row
													'value' => '',
													'value_previous' => '',
													'chain_childs' => '',
													'options' => '',
                                ),
                                'result' => array(
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
     * @Route('/app/payments', name='app_payments')
     */
    public function itemslistAction()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/paymentListViewController_'.__FUNCTION__.'.txt', 'a+') or die('Unable to open file!');
//$txt = 'paymentListViewController '.__FUNCTION__.' start ==============================================================='.PHP_EOL;
//$txt = 'this->pagination ========>'.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->pagination, TRUE));
        $this->list_filters['payment_type']['caption'] = $this->lang['PAYMENT_TYPE'];
        $this->list_filters['payment_type']['placeholder'] = $this->lang['PAYMENT_TYPE_SELECT'];
        $this->list_filters['result']['caption'] = $this->lang['PAYMENT_RESULT'];
        $this->list_filters['result']['placeholder'] = $this->lang['PAYMENT_RESULT_SELECT']; 

        $this->pagination = $this->utils->request_pagination($this->pagination);
        $this->pagination['rpp'] = $this->session->config['records_per_page'];

        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        list($res, $totalcount) = $this->utils->getResultAndCount( $this->table, $this->pagination, $this->list_filters );

        $paginator = new paginator($this->pagination['num_page'], $totalcount, $this->pagination['rpp']);

        $page_list = $paginator->pagesOnPagination($this->pagination['num_page']);

        // Filter payment type select options list
        $filter_options = '';
        //$filter_options .= '<option value=""'.(($this->list_filters['payment_type']['value'] == '')? ' selected="selected" ' : '').'>'.(($this->list_filters['payment_type']['value'] == '')? $this->lang['PAYMENT_TYPE_SELECT'] : $this->lang['PAYMENT_TYPE_ALL']).'</option>';   
        $filter_options .= '<option value=""'.(($this->list_filters['payment_type']['value'] == '')? ' selected="selected" ' : '').'>'.(($this->list_filters['payment_type']['value'] == '')? $this->lang['PAYMENT_TYPE_SELECT'] : $this->lang['PAYMENT_TYPE_ALL']).'</option>';
        $filter_options .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        $filter_select = NULL;
        $extra_select = 'ORDER BY `name`';
        $payment_type = new paymentTypeController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $rows = $payment_type->getAll( $filter_select, $extra_select);
        foreach ( $rows as $row)
        {
            $filter_options .= '<option value="' . $row['id'] . '"' . (($this->list_filters['payment_type']['value'] == $row['id']) ? ' selected="selected" ' : '') . '>' . $row['name'] . '</option>';
        }
        $this->list_filters['payment_type']['options'] = $filter_options;
        unset($filter_options);

        $filter_options = '';

        $filter_options .= '<option value=""'.(($this->list_filters['result']['value'] == '')? ' selected="selected" ' : '').'>'.(($this->list_filters['result']['value'] == '')? $this->lang['PAYMENT_RESULT_SELECT'] : $this->lang['ALL']).'</option>';

        $filter_options .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';

        $filter_options .= '<option value = 1 ' . (($this->list_filters['result']['value'] == '1') ? ' selected="selected" ' : '') . '>' . $this->lang['PAYMENT_RESULT_OK'] . '</option>';
        $filter_options .= '<option value = 0 ' . (($this->list_filters['result']['value'] == '0') ? ' selected="selected" ' : '') . '>' . $this->lang['PAYMENT_RESULT_NOT_OK'] . '</option>';

        $this->list_filters['result']['options'] = $filter_options;
        unset($filter_options);

//$txt = 'paymentListViewController '.__FUNCTION__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//fclose($this->myfile);
        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/payments.html.twig', array(
            'res' => $res,                                  // Array with the list of records
            'filters' => $this->list_filters,               // The filters data
            'pagination' => $this->pagination,              // The pagination data
            'page_list' => $page_list,                      // Array with the list of pages numbers
            'total_pages' => $paginator->getTotalPages(),   // Total amount of pages
        ));
    }
}