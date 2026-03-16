<?php

namespace src\controller\views\app;

use src\controller\baseViewController;
use src\util\paginator;

class langTextListViewController extends baseViewController
{
    private $table = 'lang_text';

    private $list_filters = array(
                                    'lang_key' => array(
                                        'type' => 'text',
                                        'caption' => '',
                                        'placeholder' => '',
                                        'width' => '0',	// if 0 uses the rest of the row
                                        'value' => '',
                                        'value_previous' => '',
                                    ),
                                    'context' => array(
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
                                'order'          => 'lang_key',
                                'order_dir'      => 'ASC',
                                'rpp'            => '',
    );

    private $folder = 'app';

    /**
     * @Route('/app/langs', name='app_langs')
     */
    public function itemslistAction()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/langTextListViewController_'.__FUNCTION__.'.txt', 'a+') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//$txt = 'this->pagination ========>'.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->pagination, TRUE));
        $this->list_filters['lang_key']['caption'] = $this->lang['LANG_TEXT_KEY'];
        $this->list_filters['lang_key']['placeholder'] = $this->lang['LANG_TEXT_KEY'];
        $this->list_filters['context']['caption'] = $this->lang['LANG_TEXT_CONTEXT'];
        $this->list_filters['context']['placeholder'] = $this->lang['LANG_TEXT_CONTEXT'];

        $this->pagination = $this->utils->request_pagination($this->pagination);
        $this->pagination['rpp'] = $this->session->config['records_per_page'];

        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        list($res, $totalcount) = $this->utils->getResultAndCount( $this->table, $this->pagination, $this->list_filters );

        $paginator = new paginator($this->pagination['num_page'], $totalcount, $this->pagination['rpp']);

        $page_list = $paginator->pagesOnPagination($this->pagination['num_page']);

        // Filter context select options list
        $filter_options = '';
        $filter_options .= '<option value=""'.(($this->list_filters['context']['value'] == '')? ' selected="selected" ' : '').'>'.(($this->list_filters['context']['value'] == '')? $this->lang['LANG_TEXT_CONTEXT_SELECT'] : $this->lang['LANG_TEXT_CONTEXT_ALL']).'</option>';
        $filter_options .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        $filter_options .= '<option value="app"' . (($this->list_filters['context']['value'] == 'app') ? ' selected="selected" ' : '') . '>app</option>';
        $filter_options .= '<option value="web"' . (($this->list_filters['context']['value'] == 'web') ? ' selected="selected" ' : '') . '>web</option>';
        $filter_options .= '<option value="errors"' . (($this->list_filters['context']['value'] == 'errors') ? ' selected="selected" ' : '') . '>errors</option>';
        $filter_options .= '<option value="security"' . (($this->list_filters['context']['value'] == 'security') ? ' selected="selected" ' : '') . '>security</option>';
        $filter_options .= '<option value="legal"' . (($this->list_filters['context']['value'] == 'legal') ? ' selected="selected" ' : '') . '>legal</option>';
        $this->list_filters['context']['options'] = $filter_options;
        unset($filter_options);

//$txt = '====================== '.__FUNCTION__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//fclose($this->myfile);
        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/langtexts.html.twig', array(
            'res' => $res,
            'filters' => $this->list_filters,
            'pagination' => $this->pagination,
            'page_list' => $page_list,
            'total_pages' => $paginator->getTotalPages(),
        ));
    }
}
