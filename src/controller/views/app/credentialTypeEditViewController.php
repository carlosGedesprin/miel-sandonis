<?php

namespace src\controller\views\app;

use \src\controller\baseViewController;
use \src\controller\entity\credentialTypeController;
use \src\controller\entity\credentialTypeDataController;

use DateTime;
use DateTimeZone;

class credentialTypeEditViewController extends baseViewController
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
    );
    private $pagination = array(
                                'num_page'       => '1',
                                'order'          => 'id',
                                'order_dir'      => 'ASC',
                                'rpp'            => '',
    );

    private $folder = 'app';

    /**
     * @Route('/app/credential_type/edit/id', name='app_credential_type_edit')
     *
     * @param POST
     *
     * @return object    Twig template
     */
    public function edititemAction( $vars )
    {
        $this->logger->info('==============='.__METHOD__.' Credential Type process '.$vars['id'].' | User '.$this->user.' ===================================================');
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/credentialTypeEditViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']) );

        $form_action = $this->folder.'/credential_type/editor';

        $this->pagination = $this->utils->request_pagination( $this->pagination );
        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        $reg = new credentialTypeController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $reg_data = new credentialTypeDataController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $reg->setId(( isset( $vars['id'] ) )? $vars['id'] : '0');
        $reg->setName( $this->utils->request_var( 'name', '', 'ALL') );
        $reg->setN8NName( $this->utils->request_var( 'n8n_name', '', 'ALL') );
        $reg->setActive( $this->utils->request_var( 'active', '1', 'ALL') );

        $data = array(
            'auth_token'    => ( isset( $_POST['auth_token']) )? $_POST['auth_token'] : $this->utils->generateFormToken($form_action),
            'submit'        => ( isset($_POST['btn_submit'])) ? true : false,
            'action'        => ( $reg->getId() == '0' )? 'add' : 'edit',
            //'credential_types_data' => $this->utils->request_var_array( 'credential_type_data', $row['code_2a'], '', 'POST', true),
            'credential_types_data' => $this->utils->request_var( 'credential_type_data', '', 'ALL', true),
        );

        $error_ajax = array();

//$txt = 'On start function start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'POST =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'REG =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
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
                    'section' => $this->lang['CREDENTIAL_TYPE_EDIT'],
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
                else if ( strlen( $reg->getName() ) > 50 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['name'],
                        'msg' => sprintf( $this->lang['ERR_NAME_LONG'], '50' ),
                    );
                }
                else
                {
                    // Check if name already exists
                    if ( $this->db->fetchOne( $reg->getTableName(), 'id', ['name' => $reg->getName()], ' AND id <> '.$reg->getId()) )
                    {
                        $error_ajax[] = array (
                            'dom_object' => ['name'],
                            'msg' => $this->lang['ERR_NAME_EXISTS'],
                        );
                    }
                }
            }

            if ( empty( $reg->getN8NName() ) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['n8n_name'],
                    'msg' => $this->lang['ERR_CREDENTIAL_TYPE_N8N_NAME_NEEDED'],
                );
            }
            else
            {
                if ( strlen( $reg->getN8NName() ) < 2 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['n8n_name'],
                        'msg' => sprintf( $this->lang['ERR_CREDENTIAL_TYPE_N8N_NAME_SHORT'], '2' ),
                    );
                }
                else if ( strlen( $reg->getN8NName() ) > 50 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['n8n_name'],
                        'msg' => sprintf( $this->lang['ERR_CREDENTIAL_TYPE_N8N_NAME_LONG'], '50' ),
                    );
                }
                else
                {
                    // Check if name already exists
                    if ( $this->db->fetchOne( $reg->getTableName(), 'id', ['n8n_name' => $reg->getN8NName()], ' AND id <> '.$reg->getId()) )
                    {
                        $error_ajax[] = array (
                            'dom_object' => ['n8n_name'],
                            'msg' => $this->lang['ERR_CREDENTIAL_TYPE_N8N_NAME_EXISTS'],
                        );
                    }
                }
            }

            if ( !sizeof( $error_ajax ) )
            {
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

                $reg_data->deleteCredentiaTypeData( $reg->getId() );

                foreach ( $data['credential_types_data'] as $credential_type_data_key => $credential_type_data_value )
                {
                    if ( !empty( $credential_type_data_value['name'] ) )
                    {
                        $reg_data->setId('' );
                        $reg_data->setCredentialType($reg->getId() );
                        $reg_data->setName( $credential_type_data_value['name'] );
                        $reg_data->setField( $credential_type_data_value['field'] );
                        $reg_data->setActive( '1' );
                        $reg_data->persistORL();
                    }
                }

                // Send success to be displayed
                $response['status'] = 'OK';
                $response['msg'] = $this->lang['CREDENTIAL_TYPE_SAVED'];
                $response['action'] = '/'.$this->folder.'/'.$this->lang['CREDENTIAL_TYPES_LINK'];
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
            }
            else
            {
                // Edit record
                if ( $reg->getRegbyId( $reg->getId() ) )
                {
                    $filter_select = [
                                      'credential_type' => $reg->getId(),
                    ];
                    $extra_select = 'ORDER BY `id`';
                    $data['credential_types_data'] = $reg_data->getAll( $filter_select, $extra_select );
                }
                else
                {
                    $_SESSION['alert'] = array('type'=>'danger', 'message'=>$this->lang['ERR_CREDENTIAL_TYPE_NOT_EXISTS']);
                    header('Location: /'.$this->folder.'/'.$this->lang['CREDENTIAL_TYPES_LINK']);
                    exit;
                }
            }
        }

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose($this->myfile);
        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/credentialTypeForm.html.twig', array(
            'reg' => $reg->getReg(),
            'filters' => $this->list_filters,
            'pagination' => $this->pagination,
            'data' => $data,
            'cancel' => '/'.$this->folder.'/'.$this->lang['CREDENTIAL_TYPES_LINK'],
        ));
    }
}