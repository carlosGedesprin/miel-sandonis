<?php

namespace src\controller\views\app;

use \src\controller\baseViewController;

use \src\controller\entity\productController;
use \src\controller\entity\productTypeController;

use DateTime;
use DateTimeZone;

class productEditViewController extends baseViewController
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
     * @Route('/app/product/edit/id', name='app_product_edit')
     *
     * @param POST
     *
     * @return object    Twig template
     */
    public function edititemAction( $vars )
    {
        $this->logger->info('==============='.__METHOD__.' Product '.$vars['id'].' | User '.$this->user.' ===================================================');
$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/productEditViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
$txt = 'POST =========='.PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']) );

        $form_action = $this->folder.'/product/editor';

        $this->pagination = $this->utils->request_pagination( $this->pagination );
        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        $reg = new productController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $reg->setId( ( isset( $vars['id'] ) )? $vars['id'] : '0');
        $reg->setProductType( $this->utils->request_var( 'product_type', '', 'ALL') );
        $reg->setName( $this->utils->request_var( 'name', '', 'ALL', true) );
        $reg->setPeriodDemo( $this->utils->request_var( 'period_demo', '', 'ALL') );
        $reg->setNumPeriodDemo( $this->utils->request_var( 'num_period_demo', '', 'ALL') );
        $reg->setPeriod( $this->utils->request_var( 'period', '', 'ALL') );
        $reg->setNumPeriod( $this->utils->request_var( 'num_period', '', 'ALL') );
        $reg->setPeriodGrace( $this->utils->request_var( 'period_grace', '', 'ALL') );
        $reg->setNumPeriodGrace( $this->utils->request_var( 'num_period_grace', '0', 'ALL') );
        $reg->setPrice( $this->utils->request_var( 'price', '', 'ALL') );
        $reg->setGenerateCommission( $this->utils->request_var( 'generate_commission', '0', 'ALL') );
        $reg->setShowInCP( $this->utils->request_var( 'show_in_cp', '1', 'ALL') );
        $reg->setActive( $this->utils->request_var( 'active', '0', 'ALL') );

        $data = array(
            'auth_token' => ( isset( $_POST['auth_token']) )? $_POST['auth_token'] : $this->utils->generateFormToken($form_action),
            'submit' => (isset($_POST['btn_submit'])) ? true : false,
            'action' => ( $reg->getId() == '0' )? 'add' : 'edit',
            'product_type_options' => '',
            'period_demo_options' => '',
            'period_options' => '',
            'period_grace_options' => '',
            'generate_commission_options' => '',
            'show_in_cp_options' => '',
            'active_options' => '',
        );
        $error_ajax = array();

//$txt = 'On start function start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'POST =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'REG =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Data =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'On start function end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        if ( $data['submit'] )
        {
            //TODO:Carlos CSRF is not well resolved, when ajax is involved to send the whole form, startup destroys $_SESSION
            // so normal submit will find session and will not log out the user
            /*
            $valid = $this->utils->verifyFormToken($form_action, $data['auth_token'], 5000);
            if (!$valid) {
                return $this->twig->render('app/'.$this->session->config['app_skin'].'/common/show_message.html.twig', array(
                    'alert_type' => 'danger',
                    'title' => $this->lang['WARNING'],
                    'message' => $this->lang['ERR_BAD_TOKEN'],
                    'redirect' => '/logout',
                ));
            }
            */
            if ( empty( $reg->getName() ) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['name'],
                    'msg' => $this->lang['ERR_NAME_NEEDED'],
                );
            }
            else
            {
                if ( strlen( $reg->getName() ) < 2 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['name'],
                        'msg' => sprintf( $this->lang['ERR_NAME_SHORT'], '2' ),
                    );
                }
                else if ( strlen( $reg->getName() ) > 100 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['name'],
                        'msg' => sprintf( $this->lang['ERR_NAME_LONG'], '100' ),
                    );
                }
                elseif ( $this->db->fetchOne( $reg->getTableName(), 'id', ['name' => $reg->getName() ], ' AND id <> '.$reg->getId()) )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['name'],
                        'msg' => $this->lang['ERR_NAME_EXISTS'],
                    );
                }
            }

            if ( empty( $reg->getProductType() ) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['product_type'],
                    'msg' => $this->lang['ERR_PRODUCT_TYPE_NEEDED'],
                );
            }

            if ( !empty( $reg->getNumPeriodDemo() ) )
            {
                if ( !is_numeric( $reg->getNumPeriodDemo() ) )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['num_period_demo'],
                        'msg' => $this->lang['ERR_PRODUCT_PERIOD_DEMO_NOT_NUMERIC'],
                    );
                }
                else
                {
                    if ( $reg->getNumPeriodDemo() < 0 || $reg->getNumPeriodDemo() > 99 )
                    {
                        $error_ajax[] = array (
                            'dom_object' => ['num_period_demo'],
                            'msg' => $this->lang['ERR_PRODUCT_PERIOD_DEMO_OUT_OF_RANGE'],
                        );
                    }
                }
            }

            if ( !empty( $reg->getNumPeriod() ) )
            {
                if ( !is_numeric( $reg->getNumPeriod() ) )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['num_period'],
                        'msg' => $this->lang['ERR_PRODUCT_PERIOD_NOT_NUMERIC'],
                    );
                }
                else
                {
                    if ( $reg->getNumPeriod() < 0 || $reg->getNumPeriod() > 99 )
                    {
                        $error_ajax[] = array (
                            'dom_object' => ['num_period_demo'],
                            'msg' => $this->lang['ERR_PRODUCT_PERIOD_OUT_OF_RANGE'],
                        );
                    }
                }
            }

            if ( !empty( $reg->getNumPeriodGrace() ) )
            {
                if ( !is_numeric( $reg->getNumPeriodGrace() ) )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['num_period_grace'],
                        'msg' => $this->lang['ERR_PRODUCT_PERIOD_GRACE_NOT_NUMERIC'],
                    );
                }
                else
                {
                    if ( $reg->getNumPeriodGrace() < 0 || $reg->getNumPeriodGrace() > 999 )
                    {
                        $error_ajax[] = array (
                            'dom_object' => ['num_period_grace'],
                            'msg' => $this->lang['ERR_PRODUCT_PERIOD_GRACE_OUT_OF_RANGE'],
                        );
                    }
                }
            }

            if ( !empty( $reg->getPrice() ))
            {
                if ( strlen( $reg->getPrice() ) > 10 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['price'],
                        'msg' => sprintf($this->lang['ERR_PRODUCT_PRICE_LONG'], '10'),
                    );
                }
                else if ( strlen( $reg->getPrice() ) < 4 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['price'],
                        'msg' => sprintf( $this->lang['ERR_PRODUCT_PRICE_SHORT'], '4' ),
                    );
                }
            }
            
            if ( $data['action'] == 'add' )
            {
                if( $reg->getGenerateCommission() == '' )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['generate_commission'],
                        'msg' => $this->lang['ERR_PRODUCT_GENERATE_COMMISSION_NEEDED'],
                    );
                }

                if( $reg->getShowInCP() == '' )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['show_in_cp'],
                        'msg' => $this->lang['ERR_PRODUCT_SHOW_IN_CP_NEEDED'],
                    );
                }

                if( $reg->getActive() == '' )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['active'],
                        'msg' => $this->lang['ERR_ACTIVE_NEEDED'],
                    );
                }
            }

            if ( !sizeof( $error_ajax ) )
            {
                if ( $reg->getPeriodDemo() == '-' ) $reg->setPeriodDemo('');
                if ( $reg->getPeriod() == '-' ) $reg->setPeriod('');
                if ( $reg->getPeriodGrace() == '-' ) $reg->setPeriodGrace('');

                if ( $data['action'] == 'add' )
                {
                    // new record
                    $reg->persistORL();
                }
                else
                {
                    // Edit record
                    $reg->persistORL();
                }

                // Send success to be displayed
                $response['status'] = 'OK';
                $response['msg'] = $this->lang['PRODUCT_SAVED'];
                $response['action'] = '/'.$this->folder.'/'.$this->lang['PRODUCTS_LINK'];
                echo json_encode($response);
                exit();
            }
            else
            {
                // Errors found

                // Renew CSRF
                //$data['auth_token'] = $this->utils->generateFormToken($form_action);

                // Send errors to be displayed
                $response['status'] = 'KO';
                foreach( $error_ajax as $key => $value )
                {
                    $response['errors'][] = $value;
                }
//$txt = 'Errors =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($response['errors'], TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                echo json_encode($response);
                exit();
            }
        }
        else
        {
            if ( $data['action'] == 'add' )
            {
                // new record
//                if ( $this->list_filters['product_type']['value'] != '0' ) $reg->setProductType( $this->list_filters['product_type']['value'] );
            }
            else
            {
                // Edit record
                if ( $reg->getRegbyId( $reg->getId() ))
                {
                    if (empty($reg->getPeriodDemo())) $reg->setPeriodDemo('-');
                    if (empty($reg->getPeriod())) $reg->setPeriod('-');
                    if (empty($reg->getPeriodGrace())) $reg->setPeriodGrace('-');
                }
                else
                {
                    $_SESSION['alert'] = array('type'=>'danger', 'message'=>$this->lang['ERR_PRODUCT_NOT_EXISTS']);
                    header('Location: /'.$this->folder.'/'.$this->lang['PRODUCTS_LINK']);
                    exit();
                }
            }
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
            $data['product_type_options'] .= '<option value="' . $row['id'] . '"' . (( $reg->getProductType() == $row['id']) ? ' selected="selected" ' : '') . '>' . $row["name"] . '</option>';
        }

        // Period demo select options list
        if ( $reg->getPeriodDemo() == '' )
        {
            $data['period_demo_options'] .= '<option value="" selected="selected"><b>'.$this->lang['PRODUCT_PERIOD_SELECT'].'</b></option>';
            $data['period_demo_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        }
        $data['period_demo_options'] .= '<option value="-" ' . (($reg->getPeriodDemo() == "-") ? ' selected="selected" ' : '') . '>'.$this->lang['PRODUCT_PERIOD_NO'].'</option>';
        $data['period_demo_options'] .= '<option value="Y" ' . (($reg->getPeriodDemo() == "Y") ? ' selected="selected" ' : '') . '>'.$this->lang['PRODUCT_PERIOD_YEAR'].'</option>';
        $data['period_demo_options'] .= '<option value="M" ' . (($reg->getPeriodDemo() == "M") ? ' selected="selected" ' : '') . '>'.$this->lang['PRODUCT_PERIOD_MONTH'].'</option>';
        $data['period_demo_options'] .= '<option value="D" ' . (($reg->getPeriodDemo() == "D") ? ' selected="selected" ' : '') . '>'.$this->lang['PRODUCT_PERIOD_DAY'].'</option>';

        // Period select options list
        if ( $reg->getPeriod() == '')
        {
            $data['period_options'] .= '<option value="" selected="selected"><b>'.$this->lang['PRODUCT_PERIOD_SELECT'].'</b></option>';
            $data['period_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        }
        $data['period_options'] .= '<option value="-" ' . (( $reg->getPeriod() == "-") ? ' selected="selected" ' : '') . '>'.$this->lang['PRODUCT_PERIOD_NO'].'</option>';
        $data['period_options'] .= '<option value="Y" ' . (( $reg->getPeriod() == "Y") ? ' selected="selected" ' : '') . '>'.$this->lang['PRODUCT_PERIOD_YEAR'].'</option>';
        $data['period_options'] .= '<option value="M" ' . (( $reg->getPeriod() == "M") ? ' selected="selected" ' : '') . '>'.$this->lang['PRODUCT_PERIOD_MONTH'].'</option>';

        // Period grace select options list
        if ( $reg->getPeriodGrace() == '')
        {
            $data['period_grace_options'] .= '<option value="" selected="selected"><b>'.$this->lang['PRODUCT_PERIOD_SELECT'].'</b></option>';
            $data['period_grace_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        }
        $data['period_grace_options'] .= '<option value="-" ' . (( $reg->getPeriodGrace() == "-") ? ' selected="selected" ' : '') . '>'.$this->lang['PRODUCT_PERIOD_GRACE_NO'].'</option>';
        $data['period_grace_options'] .= '<option value="Y" ' . (( $reg->getPeriodGrace() == "Y") ? ' selected="selected" ' : '') . '>'.$this->lang['PRODUCT_PERIOD_YEAR'].'</option>';
        $data['period_grace_options'] .= '<option value="M" ' . (( $reg->getPeriodGrace() == "M") ? ' selected="selected" ' : '') . '>'.$this->lang['PRODUCT_PERIOD_MONTH'].'</option>';
        $data['period_grace_options'] .= '<option value="D" ' . (( $reg->getPeriodGrace() == "D") ? ' selected="selected" ' : '') . '>'.$this->lang['PRODUCT_PERIOD_DAY'].'</option>';

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//fclose($this->myfile);
        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/productForm.html.twig', array(
            'reg' => $reg->getReg(),
            'filters' => $this->list_filters,
            'pagination' => $this->pagination,
            'data' => $data,
            'cancel' => '/'.$this->folder.'/'.$this->lang['PRODUCTS_LINK'],
        ));
    }

}
