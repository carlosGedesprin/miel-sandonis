<?php

namespace src\controller\views\app;

use src\controller\baseViewController;
use src\util\paginator;

class countryListViewController extends baseViewController
{
    private $table = 'country';
    private $table_name = 'country_name';

    private $list_filters = array(
                                'code_2a' => array(
                                    'type' => 'text',
                                    'caption' => '',
                                    'placeholder' => '',
                                    'width' => '0',	// if 0 uses the rest of the row
                                    'value' => '',
                                    'value_previous' => '',
                                ),
        /*
                                'iso_name' => array(
                                    'type' => 'text',
                                    'caption' => '',
                                    'placeholder' => '',
                                    'width' => '0',	// if 0 uses the rest of the row
                                    'value' => '',
                                    'value_previous' => '',
                                ),
        */
    );
    private $pagination = array(
                                'num_page'       => '1',
                                'order'          => 'id',
                                'order_dir'      => 'ASC',
                                'rpp'            => '',
    );

    private $folder = 'app';

    /**
     * @Route('/app/countries', name='app_countries')
     */
    public function itemslistAction()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/countryListViewController'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'this->pagination ========>'.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->pagination, TRUE));
        $this->list_filters['code_2a']['caption'] = $this->lang['COUNTRY_CODE_2A'];
        $this->list_filters['code_2a']['placeholder'] = $this->lang['COUNTRY_CODE_2A'];
/*
        $this->list_filters['iso_name']['caption'] = $this->lang['COUNTRY_NAME'];
        $this->list_filters['iso_name']['placeholder'] = $this->lang['COUNTRY_NAME'];
*/
        $this->pagination = $this->utils->request_pagination($this->pagination);
        $this->pagination['rpp'] = $this->session->config['records_per_page'];

        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        $reg = array(
                    'pagination' => $this->pagination,
                    'filters' => $this->list_filters,
                    'table' => 'country',
        );

        // Call api endpoint
        $api_data = array(
                            'api_key' => $this->session->config['locations_api_key'],
                            'lang' => $this->session->getLanguageCode2a(),
        );
        $route = '/get_countries_list';
        $response = $this->utils->get_from_locations_api( $route, $api_data, $reg );
//$txt = 'Api result ========>'.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($response, TRUE));
        if ( $response && $response['status'] == 'OK' )
        {
            $res = $response['msg'][0];
            $totalcount = $response['msg'][1];
        }
        else
        {
            $res = array();
            $totalcount = 0;
        }

        $paginator = new paginator($this->pagination['num_page'], $totalcount, $this->pagination['rpp']);

        $page_list = $paginator->pagesOnPagination($this->pagination['num_page']);

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//fclose($this->myfile);
        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/countries.html.twig', array(
            'res' => $res,                                  // Array with the list of records
            'filters' => $this->list_filters,               // The filters data
            'pagination' => $this->pagination,              // The pagination data
            'page_list' => $page_list,                      // Array with the list of pages numbers
            'total_pages' => $paginator->getTotalPages(),   // Total amount of pages
        ));
    }
}
