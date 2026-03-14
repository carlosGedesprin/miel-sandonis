<?php

namespace src\controller\views\app;

use src\controller\baseViewController;
use src\util\paginator;

class mailqueueListViewController extends baseViewController
{
    private $table = 'mail_queue';

    private $list_filters = array(
                                'to_name' => array(
                                    'type' => 'text',
                                    'caption' => '',
                                    'placeholder' => '',
                                    'width' => '0',	// if 0 uses the rest of the row
                                    'value' => '',
                                    'value_previous' => '',
                                ),
                                'to_address' => array(
                                    'type' => 'text',
                                    'caption' => '',
                                    'placeholder' => '',
                                    'width' => '0',	// if 0 uses the rest of the row
                                    'value' => '',
                                    'value_previous' => '',
                                ),
                                'template' => array(
                                    'type' => 'select',
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
                                'order_dir'      => 'DESC',
                                'rpp'            => '',
    );

    private $folder = 'app';

    /**
     * @Route('/app/mail_queues', name='app_mailqueues')
     */
    public function itemslistAction()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/mailqueueListViewController_'.__FUNCTION__.'.txt', 'a+') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//$txt = 'POST =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        $this->list_filters['to_name']['caption'] = $this->lang['MAIL_QUEUE_TO_NAME'];
        $this->list_filters['to_name']['placeholder'] = $this->lang['ENTER_NAME'];
        $this->list_filters['to_address']['caption'] = $this->lang['MAIL_QUEUE_TO_ADDRESS'];
        $this->list_filters['to_address']['placeholder'] = $this->lang['ENTER_ADDRESS'];
        $this->list_filters['template']['caption'] = $this->lang['MAIL_QUEUE_TEMPLATE'];
        $this->list_filters['template']['placeholder'] = $this->lang['MAIL_QUEUE_TEMPLATE_SELECT'];

        $this->pagination = $this->utils->request_pagination($this->pagination);
        $this->pagination['rpp'] = $this->session->config['records_per_page'];

        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        list($res, $totalcount) = $this->utils->getResultAndCount( $this->table, $this->pagination, $this->list_filters );

        $paginator = new paginator($this->pagination['num_page'], $totalcount, $this->pagination['rpp']);

        $page_list = $paginator->pagesOnPagination($this->pagination['num_page']);

        // Filter templates select options list
        $filter_options = '';
        $filter_options .= '<option value=""'.(($this->list_filters['template']['value'] == '')? ' selected="selected" ' : '').'>'.(($this->list_filters['template']['value'] == '')? $this->lang['MAIL_QUEUE_TEMPLATE_SELECT'] : $this->lang['MAIL_QUEUE_TEMPLATE_SELECT_ALL']).'</option>';
        $filter_options .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        $rows = $this->db->querySQL('SELECT DISTINCT `template` FROM `mail_queue`');
        foreach ( $rows as $row)
        {
            $filter_options .= '<option value="' . $row['template'] . '"' . (($this->list_filters['template']['value'] == $row['template']) ? ' selected="selected" ' : '') . '>' . $row['template'] . '</option>';
        }
        $this->list_filters['template']['options'] = $filter_options;
        unset($filter_options);

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose($this->myfile);
        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/mail_queues.html.twig', array(
            'res' => $res,                                  // Array with the list of records
            'filters' => $this->list_filters,               // The filters data
            'pagination' => $this->pagination,              // The pagination data
            'page_list' => $page_list,                      // Array with the list of pages numbers
            'total_pages' => $paginator->getTotalPages(),   // Total amount of pages
        ));
    }
}
