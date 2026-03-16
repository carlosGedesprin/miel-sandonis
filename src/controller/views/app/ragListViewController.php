<?php

namespace src\controller\views\app;

use src\controller\baseViewController;
use src\controller\entity\accountController;
use \src\util\paginator;

class ragListViewController extends baseViewController
{
    private $table = 'rag';

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
    );
    private $pagination = array(
                                'num_page'       => '1',
                                'order'          => 'id',
                                'order_dir'      => 'ASC',
                                'rpp'            => '',
    );

    private $folder = 'app';

    /**
     * @Route('/app/rags', name='app_rags')
     */
    public function itemslistAction()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/ragListViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'this->pagination ========>'.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->pagination, TRUE));
        $this->list_filters['name']['caption'] = $this->lang['RAG_NAME'];
        $this->list_filters['name']['placeholder'] = $this->lang['RAG_NAME'];
        $this->list_filters['account']['caption'] = $this->lang['ACCOUNT'];
        $this->list_filters['account']['placeholder'] = $this->lang['ACCOUNT'];

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

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose($this->myfile);
        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/rags.html.twig', array(
            'res' => $res,                                  // Array with the list of records
            'filters' => $this->list_filters,               // The filters data
            'pagination' => $this->pagination,              // The pagination data
            'page_list' => $page_list,                      // Array with the list of pages numbers
            'total_pages' => $paginator->getTotalPages(),   // Total amount of pages
        ));
    }
}
