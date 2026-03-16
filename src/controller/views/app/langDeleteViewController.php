<?php

namespace src\controller\views\app;

use \src\controller\baseViewController;

use \src\controller\entity\langController;
use \src\controller\entity\langNameController;

class langDeleteViewController extends baseViewController
{
    private $table = 'lang';
    private $table_name = 'lang_name';

    private $list_filters = array(
                                'code_2a' => array(
                                    'type' => 'text',
                                    'caption' => '',
                                    'placeholder' => '',
                                    'width' => '0',	// if 0 uses the rest of the row
                                    'value' => '',
                                    'value_previous' => '',
                                ),
                                'iso_name' => array(
                                    'type' => 'text',
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
                                'order_dir'      => 'ASC',
                                'rpp'            => '',
    );

    private $folder = 'app';

    /**
     * @Route('/app/lang/delete/id', name='app_lang_delete')
     *
     * @param POST
     *
     * @return object    Twig template
     */
    public function deleteitemAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/langController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Lang to delete '.$vars['id'].PHP_EOL; fwrite($this->myfile, $txt);

        $form_action = $this->folder.'/lang/delete';

        $this->pagination = $this->utils->request_pagination( $this->pagination );
        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        $reg = new langController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $reg_name = new langNameController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $reg->getRegbyId( ( isset( $vars['id'] ) )? $vars['id'] : '0' );

        $data = array(
            'auth_token' => ( isset( $_POST['auth_token']) )? $_POST['auth_token'] : $this->utils->generateFormToken($form_action),
            'submit' => (isset($_POST['btn_submit'])) ? true : false,
            'action' => 'delete',
            'active_options' => '',
            'lang_names' => array(),
        );

//$txt = 'POST =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'REG =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        if ( !isset( $vars['id'] ) || $vars['id'] == '' || $vars['id'] == '0' )
        {
            $_SESSION['alert'] = array(
                                        'type'          => 'danger',
                                        'message'       => $this->lang['ERR_LANG_NOT_EXISTS'],
                                        'filters'       => $this->list_filters,
                                        'pagination'    => $this->pagination,
            );
            header('Location: /'.$this->folder.'/'.$this->lang['LANGS_LINK']);
            exit;
        }

        $errors = array();

        /*------ Felix ----------
        $api_data = array(
            'reg' => $reg->getReg(),
            'data' => $data,
            'user' => $this->user,
            'lang_code_2a' => $this->session->getLanguageCode2a(),
        );
        ------ Felix ----------*/

        if ( $data['submit'] )
        {
            $errors = $reg->checkDelete();
//$txt = 'Errors received from api'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($errors, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
            
            if ( $errors['status'] == 'ok' )
            {
                $this->logger->info('==============='.__METHOD__.' Lang '.$vars['id'].' | User '.$this->user.' ===================================================');

                //$reg->delete();

                // Send success to be displayed
                $response['status'] = 'OK';
                $response['msg'] = $this->lang['LANG_DELETED'];
                $response['action'] = '/'.$this->folder.'/langs';
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

                if ( $errors['status'] == 'KO' )
                {
                    $response['errors'][] = $errors['msg'];
                }
                elseif ( $errors['status'] == 'errors' )
                {
//$txt = 'Errors from api'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($errors['msg'], TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                    foreach ( $errors['msg'] as $key => $value )
                    {
//$txt = 'Error key ('.$key.')'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($value, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

                        $response['errors'][$key]['dom_object'] = $value[0]['dom_object'];
                        if ( is_array( $value[0]['msg'] ) )
                        {
//$txt = 'Error message array descomp '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($value, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                            $response['errors'][$key]['msg'] = sprintf($this->lang[$value[0]['msg'][0]], $value[0]['msg'][1]);
                        }
                        else
                        {
//$txt = 'Error message string descomp ('.$value[0]['msg'].')'.PHP_EOL; fwrite($this->myfile, $txt);
                            $response['errors'][$key]['msg'] = $this->lang[$value[0]['msg']];
                        }
                    }
                }

                echo json_encode($response);
                exit();
            }
        }
        else
        {
        }

        //********* Names treatment start ******************
//$txt = 'Names from database =====================>'.PHP_EOL; fwrite($this->myfile, $txt);
        $rows = $reg->getActiveAndPreActive();
        // This language in his language
        if ( $reg->getCode2a() )
        {
            if (!isset($data['names'][$reg->getCode2a()][$reg->getCode2a()]))
            {
                $rows[] = array(
                    'code_2a' => $reg->getCode2a(),
                );

            }
        }
//$txt = 'Langs active and pre-active =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($rows, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        foreach ( $rows as $row )
        {
//$txt = 'Lang to treat lang_code_2a ('.$reg->getCode2a().') lang_2a ('.$row['code_2a'].')'.PHP_EOL; fwrite($this->myfile, $txt);
            $data['names'][$row['code_2a']]['lang_code_2a'] = $row['code_2a'];
            $reg_name->getRegbyCodeAndLang( $reg->getCode2a(), $row['code_2a'] );
//$txt = 'Lang name in ('.$row['code_2a'].')'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg_name->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
            $data['names'][$row['code_2a']]['lang_name'] = $reg_name->getName();
        }
//$txt = 'Names in data names =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($data['names'], TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
            //********* Names treatment end ******************

        // active select options list
        if ( $reg->getActive() == '' )
        {
            $data['active_options'] .= '<option value="" selected="selected"><b>' . $this->lang['SELECT'] . '</b></option>';
            $data['active_options'] .= '<option disabled>' . str_repeat('&#x2500', 16) . '</option>';
        }
        $data['active_options'] .= '<option value="0"' . (($reg->getActive() == '0') ? ' selected="selected" ' : '') . '>'.$this->lang['NO'].'</option>';
        $data['active_options'] .= '<option value="1"' . (($reg->getActive() == '1') ? ' selected="selected" ' : '') . '>'.$this->lang['YES'].'</option>';
        $data['active_options'] .= '<option value="2"' . (($reg->getActive() == '2') ? ' selected="selected" ' : '') . '>'.$this->lang['PRE_ACTIVE'].'</option>';

//$txt = '======================'.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose($this->myfile);
        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/langForm.html.twig', array(
            'reg' => $reg->getReg(),
            'filters' => $this->list_filters,
            'pagination' => $this->pagination,
            'data' => $data,
            'cancel' => '/'.$this->folder.'/langs',
        ));
    }
}
