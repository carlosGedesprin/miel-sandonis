<?php

namespace src\controller\views\app;

use src\controller\baseViewController;

use \src\controller\entity\sectorController;

use \src\util\paginator;

class subSectorListViewController extends baseViewController
{
    private $table = 'sub_sector';

    private $list_filters = array(
                                    'name' => array(
                                        'type' => 'text',
                                        'caption' => '',
                                        'placeholder' => '',
                                        'width' => '0',	// if 0 uses the rest of the row
                                        'value' => '',
                                        'value_previous' => '',
                                    ),
                                    'sector' => array(
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
     * @Route('/app/sub_sectors', name='app_sub_sectors')
     */
    public function itemslistAction()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/subSectorListViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'this->pagination ========>'.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->pagination, TRUE));
        $this->list_filters['name']['caption'] = $this->lang['SUB_SECTOR_NAME'];
        $this->list_filters['name']['placeholder'] = $this->lang['SUB_SECTOR_NAME'];
        $this->list_filters['sector']['caption'] = $this->lang['SECTOR'];
        $this->list_filters['sector']['placeholder'] = $this->lang['SECTOR'];

        $this->pagination = $this->utils->request_pagination($this->pagination);
        $this->pagination['rpp'] = $this->session->config['records_per_page'];

        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        list($res, $totalcount) = $this->utils->getResultAndCount( $this->table, $this->pagination, $this->list_filters );

        $paginator = new paginator($this->pagination['num_page'], $totalcount, $this->pagination['rpp']);

        $page_list = $paginator->pagesOnPagination($this->pagination['num_page']);

        // Filter sector select options list
        $filter_options = '';
        $filter_options .= '<option value=""'.(($this->list_filters['sector']['value'] == '')? ' selected="selected" ' : '').'>'.(($this->list_filters['sector']['value'] == '')? $this->lang['SECTOR_SELECT'] : $this->lang['SECTOR_ALL']).'</option>';
        $filter_options .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        $filter_select = NULL;
        $extra_select = 'ORDER BY `name`';
        if ( $this->group != GROUP_SUPER_ADMIN && $this->group != GROUP_ADMIN ) {
            $filter_select = array('show_to_staff' => '1');
        }
        $sector = new sectorController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $rows = $sector->getAll( $filter_select, $extra_select);
        foreach ( $rows as $row )
        {
            $filter_options .= '<option value="' . $row['id'] . '"' . (($this->list_filters['sector']['value'] == $row['id']) ? ' selected="selected" ' : '') . '>'.$row['name'].' - '.$row['id'] . '</option>';
        }
        $this->list_filters['sector']['options'] = $filter_options;
        unset($filter_options);

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//fclose($this->myfile);
        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/subSectors.html.twig', array(
            'res' => $res,
            'filters' => $this->list_filters,
            'pagination' => $this->pagination,
            'page_list' => $page_list,
            'total_pages' => $paginator->getTotalPages(),
        ));
    }
}
