<?php

namespace src\controller\views\app;

use src\controller\baseViewController;
use src\util\paginator;

class cityListViewController extends baseViewController
{
    private $table = 'city';
    private $table_name = 'city_name';

    private $list_filters = array(
                                    'country_code_2a' => array(
                                        'type' => 'select',
                                        'caption' => '',
                                        'placeholder' => '',
                                        'width' => '0',	// if 0 uses the rest of the row
                                        'value' => '',
                                        'value_previous' => '',
                                        'chain_childs' => 'region_code',
                                        'options' => '',
                                    ),
                                    'region_code' => array(
                                        'type' => 'select',
                                        'caption' => '',
                                        'placeholder' => '',
                                        'width' => '0',	// if 0 uses the rest of the row
                                        'value' => '',
                                        'value_previous' => '',
                                        'chain_childs' => '',
                                        'options' => '',
                                    ),
            /*
                                    'city_name' => array(
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
     * @Route('/app/cities', name='app_cities')
     */
    public function itemslistAction()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/cityListViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'this->pagination ========>'.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->pagination, TRUE));
        $this->list_filters['country_code_2a']['caption'] = $this->lang['REGION_COUNTRY_CODE_2A'];
        $this->list_filters['country_code_2a']['placeholder'] = $this->lang['REGION_COUNTRY_CODE_2A'];
        $this->list_filters['region_code']['caption'] = $this->lang['CITY_REGION_CODE'];
        $this->list_filters['region_code']['placeholder'] = $this->lang['CITY_REGION_CODE'];
/*
        $this->list_filters['city_name']['caption'] = $this->lang['CITY_NAME'];
        $this->list_filters['city_name']['placeholder'] = $this->lang['CITY_NAME'];
*/
        $this->pagination = $this->utils->request_pagination($this->pagination);
        $this->pagination['rpp'] = $this->session->config['records_per_page'];

        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );
//$txt = 'this->list_filters ========>'.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->list_filters, TRUE));

        $reg = array(
                    'pagination' => $this->pagination,
                    'filters' => $this->list_filters,
                    'table' => 'city',
        );

        // Call api endpoint
        $api_data = array(
                            'api_key' => $this->session->config['locations_api_key'],
                            'lang' => $this->session->getLanguageCode2a(),

        );
        $route = '/get_cities_list';
        $response = $this->utils->get_from_locations_api( $route, $api_data, $reg );
//$txt = 'Api response cities list ========> '.PHP_EOL;fwrite($this->myfile, $txt);
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

        // Filter country select options list
        $filter_options = '';
        $filter_options .= '<option value=""'.(($this->list_filters['country_code_2a']['value'] == '')? ' selected="selected" ' : '').'>'.(($this->list_filters['country_code_2a']['value'] == '0')? $this->lang['COUNTRY_SELECT'] : $this->lang['COUNTRY_ALL']).'</option>';
        $filter_options .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        $filter_select = NULL;
//        if ( $this->group != GROUP_SUPER_ADMIN && $this->group != GROUP_ADMIN ) {
//            $filter_select = array('show_to_staff' => '1');
//        }

        $api_data = array(
                            'api_key' => $this->session->config['locations_api_key'],
                            'lang' => $this->session->getLanguageCode2a(),

        );
        $route = '/get_countries';
        $api_response = $this->utils->get_from_locations_api( $route, $api_data );
//$txt = 'Api response for filter countries ==========> '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($api_response['msg'], TRUE));$txt = PHP_EOL; fwrite($this->myfile, $txt);

        if ( $api_response['status'] == 'OK')
        {
//$txt = 'Api response status ====> OK'.PHP_EOL; fwrite($this->myfile, $txt);
            foreach ( $api_response['msg'] as $key => $country)
            {
                $filter_options .= '<option';
                $filter_options .= ' value="'.$country['country_code_2a'].'"';
                $filter_options .= (($this->list_filters['country_code_2a']['value'] == $country['country_code_2a'])? ' selected="selected" ' : '');
                $filter_options .= '>'.$country['name'];
                $filter_options .= ( $country['lang_code_2a'] != $this->session->getLanguageCode2a() )? ' ['.$country['lang_code_2a'].']' : '';
                $filter_options .= '</option>';
            }
        }
        $this->list_filters['country_code_2a']['options'] = $filter_options;
        unset($filter_options);

        // Filter region select options list
        $filter_options = '';
//$txt = 'country_code_2a on country filter ==========> '.$this->list_filters['country_code_2a']['value'].PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'region_code on region filter ==========> '.$this->list_filters['region_code']['value'].PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
        if ( empty( $this->list_filters['country_code_2a']['value'] ) )
        {
            $filter_options .= '<option value="" selected="selected">'.$this->lang['COUNTRY_SELECT'].'</option>';
        }
        else
        {
            $filter_options .= '<option value=""'.(($this->list_filters['region_code']['value'] == '')? ' selected="selected" ' : '').'>'.(($this->list_filters['region_code']['value'] == '0')? $this->lang['REGION_SELECT'] : $this->lang['REGIONS_ALL']).'</option>';
            $filter_options .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
            $filter_select = NULL;
    //        if ( $this->group != GROUP_SUPER_ADMIN && $this->group != GROUP_ADMIN ) {
    //            $filter_select = array('show_to_staff' => '1');
    //        }

            $api_data = array(
                                'api_key' => $this->session->config['locations_api_key'],
                                'lang' => $this->session->getLanguageCode2a(),
            );

            if ( !empty( $this->list_filters['country_code_2a']['value'] ) ) $api_data['country_code_2a'] = $this->list_filters['country_code_2a']['value'];
//$txt = 'Api data ==========> '.$this->list_filters['country_code_2a']['value'].PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($api_data, TRUE));$txt = PHP_EOL; fwrite($this->myfile, $txt);

            $route = '/get_regions';
            $api_response = $this->utils->get_from_locations_api( $route, $api_data );
//$txt = 'Api response for filter regions ==========> '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($api_response['msg'], TRUE));$txt = PHP_EOL; fwrite($this->myfile, $txt);

            if ( $api_response && $api_response['status'] == 'OK')
            {
//$txt = 'Api response status ====> OK'.PHP_EOL; fwrite($this->myfile, $txt);
                foreach ( $api_response['msg'] as $key => $region)
                {
                    $filter_options .= '<option';
                    $filter_options .= ' value="'.$region['region_code'].'"';
                    $filter_options .= (($this->list_filters['region_code']['value'] == $region['region_code'])? ' selected="selected" ' : '');
                    $filter_options .= '>'.$region['name'];
                    $filter_options .= ( $region['region_code'] != $this->session->getLanguageCode2a() )? ' ['.$region['region_code'].']' : '';
                    $filter_options .= '</option>';
                }
            }
            else
            {
                $res = NULL;
            }
        }
        $this->list_filters['region_code']['options'] = $filter_options;
        unset($filter_options);

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//fclose($this->myfile);
        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/cities.html.twig', array(
            'res' => $res,                                  // Array with the list of records
            'filters' => $this->list_filters,               // The filters data
            'pagination' => $this->pagination,              // The pagination data
            'page_list' => $page_list,                      // Array with the list of pages numbers
            'total_pages' => $paginator->getTotalPages(),   // Total amount of pages
        ));
    }
}
