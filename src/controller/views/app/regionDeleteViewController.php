<?php

namespace src\controller\views\app;

use \src\controller\baseViewController;


use \src\controller\entity\regionController;
use \src\controller\entity\regionNameController;

use \src\controller\entity\langController;

class regionDeleteViewController extends baseViewController
{
    private $list_filters = array(
                                    'country_code_2a' => array(
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
                                    'country_name' => array(
                                        'type' => 'text',
                                        'caption' => '',
                                        'placeholder' => '',
                                        'width' => '0',	// if 0 uses the rest of the row
                                        'value' => '',
                                        'value_previous' => '',
                                    ),
                                    'region_name' => array(
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
     * @Route('/app/region/delete/id', name='app_region_delete')
     *
     * @param POST
     *
     * @return object    Twig template
     */
    public function deleteitemAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/regionDeleteViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Region to delete '.$vars['region_code'].PHP_EOL; fwrite($this->myfile, $txt);

        $form_action = $this->folder.'/region/delete';

        $this->pagination = $this->utils->request_pagination( $this->pagination );
        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        $reg = new regionController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $reg_name = new regionNameController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $lang = new langController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $reg->getRegbyRegionCode( $vars['region_code'] );

        $data = array(
            'auth_token' => ( isset( $_POST['auth_token']) )? $_POST['auth_token'] : $this->utils->generateFormToken($form_action),
            'submit' => (isset($_POST['btn_submit'])) ? true : false,
            'action' => 'delete',
            //********* Names treatment openges start ******************
            'names' => array(),
            //********* Names treatment openges end ******************
            'country_options' => '',
        );

//$txt = 'POST =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'REG =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        if ( !isset( $vars['region_code'] ) || $vars['region_code'] == '' || $vars['region_code'] == '0' )
        {
            $_SESSION['alert'] = array(
                                        'type'          => 'danger',
                                        'message'       => $this->lang['ERR_REGION_NOT_EXISTS'],
                                        'filters'       => $this->list_filters,
                                        'pagination'    => $this->pagination,
            );
            header('Location: /'.$this->folder.'/regions');
            exit;
        }

        $errors = array();

        //********* Names treatment openges start ******************
        $langs = $lang->getActiveAndPreActive();
//$txt = 'Active & Preactive langs =====================>'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($langs, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        foreach ( $langs as $lang_key => $lang_value )
        {
            $data['names'][$lang_value['code_2a']]['lang_code_2a'] = $lang_value['code_2a'];
            $data['names'][$lang_value['code_2a']]['region_name'] = $this->utils->request_var_array( 'region_name', $lang_value['code_2a'], '', 'POST', true);
        }
//$txt = 'Data names from form =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($data['names'], TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        //********* Names treatment openges end ******************

        if ( $data['submit'] )
        {
            $errors = $reg->checkDelete();
//$txt = 'Errors received from api'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($errors, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
            
            if ( $errors['status'] == 'OK' )
            {
                $this->logger->info('==============='.__METHOD__.' Region '.$vars['region_code'].' | User '.$this->user.' ===================================================');

                //********* Names treatment openges start ******************
//$txt = 'Delete names to table =====================>'.PHP_EOL; fwrite($this->myfile, $txt);

//$txt = 'Names from form =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($data['names'], TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

//$txt = 'Deleting to names api =========='.PHP_EOL; fwrite($this->myfile, $txt);
                $rows = $lang->getActiveAndPreActive();
//$txt = 'Langs active and pre-active =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($rows, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                foreach ( $rows as $row )
                {
//$txt = 'Lang to treat ====> ('.$row['code_2a'].')'.PHP_EOL; fwrite($this->myfile, $txt);
                    $reg_name->getRegbyRegionCodeAndLang2a( $reg->getRegionCode(), $row['code_2a'] );
//$txt = 'County name to treat ====> '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg_name->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                    $reg_name->delete();
//$txt = 'Region name deleted ====> '.$reg_name->getRegionCode().PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
                }
                //********* Names treatment openges end ******************

                $reg->delete();

                // Send success to be displayed
                $response['status'] = 'OK';
                $response['msg'] = $this->lang['REGION_DELETED'];
                $response['action'] = '/'.$this->folder.'/regions';
                echo json_encode( $response );
                exit();
            }
            else
            {
                // Errors found

                // Renew CSRF
                //$data['auth_token'] = $this->utils->generateFormToken($form_action);

                // Send errors to be displayed
                $response['status'] = 'KO';

                $error_key = 0;

                foreach ( $errors['msg'] as $key => $value )
                {
//$txt = 'Error key ('.$key.')'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($value, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

                    $response['errors'][$error_key] = array (
                                                                'dom_object' => NULL,
                                                                'msg' => ''
                    );

                    $error = $value[0];

                    if ( $error['msg'][1] != '' )
                    {
                        $response['errors'][$error_key]['msg'] = sprintf($this->lang[$error['msg'][0]], $error['msg'][1]);
                    }
                    else
                    {
                        $response['errors'][$error_key]['msg'] = $this->lang[$error['msg'][0]];
                    }

                    $error_key++;
                }

                echo json_encode($response);
                exit();
            }
        }
        else
        {
            //********* Names treatment openges start ******************
//$txt = 'Names from database =====================>'.PHP_EOL; fwrite($this->myfile, $txt);
            $langs = $lang->getActiveAndPreActive();
//$txt = 'Active & Preactive langs =====================>'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($langs, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
            foreach ( $langs as $lang_key => $lang_value )
            {
//$txt = 'Lang to treat ('.$lang_value['code_2a'].')'.PHP_EOL; fwrite($this->myfile, $txt);
                $data['names'][$lang_value['code_2a']]['lang_code_2a'] = $lang_value['code_2a'];
                $reg_name->getRegbyRegionCodeAndLang2a( $reg->getRegionCode(), $lang_value['code_2a'] );
//$txt = 'Region '.$reg->getCode2a().' name in ('.$lang_value['code_2a'].') is ('.$reg_name->getName().')'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg_name->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                $data['names'][$lang_value['code_2a']]['region_name'] = $reg_name->getName();
            }
//$txt = 'Names in data names from db =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($data['names'], TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
            //********* Names treatment openges end ******************
        }

        // Country select options list
        require_once(APP_ROOT_PATH.'/src/util/view_selects/country_region_all.php');

//$txt = '======================'.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose($this->myfile);
        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/regionForm.html.twig', array(
            'reg' => $reg->getReg(),
            'filters' => $this->list_filters,
            'pagination' => $this->pagination,
            'data' => $data,
            'cancel' => '/'.$this->folder.'/regions',
        ));
    }
}
