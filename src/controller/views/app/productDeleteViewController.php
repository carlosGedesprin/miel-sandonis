<?php

namespace src\controller\views\app;

use \src\controller\baseViewController;

use \src\controller\entity\productController;

use \src\controller\entity\planController;
use \src\controller\entity\productTypeController;

class productDeleteViewController extends baseViewController
{
    private $list_filters = array(
                                'name' => array(
													'type' => 'text',
													'caption' => '',
													'placeholder' => '',
													'width' => '0',	// if 0 uses the rest of the row
													'value' => '',
													'value_previous' => '',
                                ),
                                'product_type' => array(
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
     * @Route('/app/product/delete/id', name='app_product_delete')
     *
     * @param POST
     *
     * @return object    Twig template
     */
    public function deleteitemAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/productDeleteViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Vars =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($vars, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $form_action = $this->folder.'/product/delete';

        $this->pagination = $this->utils->request_pagination( $this->pagination );
        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        if ( !isset( $vars['id']) || $vars['id'] == '' || $vars['id'] == '0' )
        {
            $_SESSION['alert'] = array(
                'type'          => 'danger',
                'message'       => $this->lang['ERR_PRODUCT_NOT_EXISTS'],
                'filters'       => $this->list_filters,
                'pagination'    => $this->pagination,
            );
            header('Location: /'.$this->folder.'/'.$this->lang['PRODUCTS_LINK']);
            exit();
        }

        $reg = new productController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $reg->getRegbyId( $vars['id'] );

        $data = array(
            'auth_token' => ( isset( $_POST['auth_token']) )? $_POST['auth_token'] : $this->utils->generateFormToken($form_action),
            'submit' => (isset($_POST['btn_submit'])) ? true : false,
            'action' => 'delete',
            'plan_options' => '',
            'product_type_options' => '',
            'period_demo_options' => '',
            'period_options' => '',
            'period_grace_options' => '',
            'visits_options' => '',
        );

        $error_ajax = array();

        if ( $data['submit'] )
        {
            // CSRF Token validation
            /*
            $valid = $this->utils->verifyFormToken($form_action, $data['auth_token'], 1000);
            if(!$valid){
                return $this->twig->render('app/'.$this->session->config['app_skin'].'/common/show_message.html.twig', array(
                    'alert_type' => 'danger',
                    'title' => $this->lang['WARNING'],
                    'message' => $this->lang['ERR_BAD_TOKEN'],
                    'redirect' => '/logout',
                ));
            }
            */

            if ( $this->db->fetchOne('website', 'id', ['product' => $reg->getId() ] ))
            {
                $error_ajax[] = array (
                    'dom_object' => [],
                    'msg' => $this->lang['ERR_PRODUCT_HAS_WEBSITES'],
                );
            }

            if ( !sizeof( $error_ajax ) )
            {
                $this->logger->info('==============='.__METHOD__.' Product '.$vars['id'].' | User '.$this->user.' ===================================================');

                $reg->delete();

                $this->pagination['num_page'] = '1';

                // Send success to be displayed
                $response['status'] = 'OK';
                $response['msg'] = $this->lang['PRODUCT_DELETED'];
                $response['action'] = '/'.$this->folder.'/'.$this->lang['PRODUCTS_LINK'];
                echo json_encode($response);
                exit();
            }
            else
            {
                // Errors found

                // Renew CSRF
//                $data['auth_token'] = $this->utils->generateFormToken($form_action);
                $response['status'] = 'KO';
                foreach( $error_ajax as $key => $value )
                {
                    $response['errors'][] = $value;
                }
                // Send errors to be displayed
                echo json_encode($response);
                exit();
            }
        }
        else
        {
            if( !empty( $reg->getId() ) )
            {
            }
            else
            {
                $_SESSION['alert'] = array(
                                            'type'          => 'danger',
                                            'message'       => $this->lang['ERR_PRODUCT_NOT_EXISTS'],
                                            'filters'       => $this->list_filters,
                                            'pagination'    => $this->pagination,
                );
                header('Location: /'.$this->folder.'/'.$this->lang['PRODUCTS_LINK']);
                exit();
            }
        }

        // Plan select options list
        if ( $reg->getPlan() == '')
        {
            $data['plan_options'] .= '<option value="" selected="selected"><b>'.$this->lang['PLAN_SELECT'].'</b></option>';
            $data['plan_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        }
        $filter_select = array(
                                'active' => '1'
        );
        $extra_select = 'ORDER BY `name`';
        $plan = new planController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $rows = $plan->getAll( $filter_select, $extra_select);
        foreach ($rows as $row) {
            $data['plan_options'] .= '<option value="' . $row['id'] . '"' . (( $reg->getPlan() == $row['id']) ? ' selected="selected" ' : '') . '>' . $row['name'] . '</option>';
        }

        // Product Type select options list
        if ( $reg->getProductType() == '')
        {
            $data['product_type_options'] .= '<option value="" selected="selected"><b>'.$this->lang['PRODUCT_TYPE_SELECT'].'</b></option>';
            $data['product_type_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        }
        $filter_select = array(
                                'active' => '1'
        );
        $extra_select = 'ORDER BY `name`';
        $product_type = new productTypeController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $rows = $product_type->getAll( $filter_select, $extra_select);
        foreach ($rows as $row) {
            $data['product_type_options'] .= '<option value="' . $row['id'] . '"' . (( $reg->getProductType() == $row['id']) ? ' selected="selected" ' : '') . '>' . $row['name'] . '</option>';
        }

        // Period demo select options list
        if ( $reg->getPeriodDemo() == '' )
        {
            $data['period_demo_options'] .= '<option value="" selected="selected"><b>'.$this->lang['PRODUCT_PERIOD_SELECT'].'</b></option>';
            $data['period_demo_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        }
        $data['period_demo_options'] .= '<option value="Y" ' . (($reg->getPeriodDemo() == "Y") ? ' selected="selected" ' : '') . '>'.$this->lang['PLAN_PERIOD_DEMO_YEAR'].'</option>';
        $data['period_demo_options'] .= '<option value="M" ' . (($reg->getPeriodDemo() == "M") ? ' selected="selected" ' : '') . '>'.$this->lang['PLAN_PERIOD_DEMO_MONTH'].'</option>';
        $data['period_demo_options'] .= '<option value="D" ' . (($reg->getPeriodDemo() == "D") ? ' selected="selected" ' : '') . '>'.$this->lang['PLAN_PERIOD_DEMO_DAY'].'</option>';

        // Period select options list
        if ( $reg->getPeriod() == '')
        {
            $data['period_options'] .= '<option value="" selected="selected"><b>'.$this->lang['PRODUCT_PERIOD_SELECT'].'</b></option>';
            $data['period_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        }
        $data['period_options'] .= '<option value="Y" ' . (( $reg->getPeriod() == "Y") ? ' selected="selected" ' : '') . '>'.$this->lang['PRODUCT_PERIOD_YEAR'].'</option>';
        $data['period_options'] .= '<option value="M" ' . (( $reg->getPeriod() == "M") ? ' selected="selected" ' : '') . '>'.$this->lang['PRODUCT_PERIOD_MONTH'].'</option>';

        // Period grace select options list
        if ( $reg->getPeriodGrace() == '')
        {
            $data['period_grace_options'] .= '<option value="" selected="selected"><b>'.$this->lang['PRODUCT_PERIOD_SELECT'].'</b></option>';
            $data['period_grace_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        }
        $data['period_grace_options'] .= '<option value="Y" ' . (( $reg->getPeriodGrace() == "Y") ? ' selected="selected" ' : '') . '>'.$this->lang['PRODUCT_PERIOD_YEAR'].'</option>';
        $data['period_grace_options'] .= '<option value="M" ' . (( $reg->getPeriodGrace() == "M") ? ' selected="selected" ' : '') . '>'.$this->lang['PRODUCT_PERIOD_MONTH'].'</option>';
        $data['period_grace_options'] .= '<option value="D" ' . (( $reg->getPeriodGrace() == "D") ? ' selected="selected" ' : '') . '>'.$this->lang['PRODUCT_PERIOD_DAY'].'</option>';

        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/productForm.html.twig', array(
            'reg' => $reg->getReg(),
            'filters' => $this->list_filters,
            'pagination' => $this->pagination,
            'data' => $data,
            'cancel' => '/'.$this->folder.'/'.$this->lang['PRODUCTS_LINK'],
        ));
    }
}
