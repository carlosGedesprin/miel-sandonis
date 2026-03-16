<?php

namespace src\controller\views\app;

use \src\controller\baseViewController;

use \src\controller\entity\accountController;
use \src\controller\entity\credentialController;
use \src\controller\entity\credentialDataController;
use \src\controller\entity\credentialTypeController;
use \src\controller\entity\credentialTypeDataController;

use DateTime;
use DateTimeZone;

class credentialEditViewController extends baseViewController
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
     * @Route('/app/credential/edit/id', name='app_credential_edit')
     *
     * @param POST
     *
     * @return object    Twig template
     */
    public function edititemAction( $vars )
    {
        $this->logger->info('==============='.__METHOD__.' Credential process '.$vars['id'].' | User '.$this->user.' ===================================================');
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/credentialEditViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']) );

        $form_action = $this->folder.'/credential/editor';

        $this->pagination = $this->utils->request_pagination( $this->pagination );
        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        $reg = new credentialController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $reg_original = new credentialController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $reg_data = new credentialDataController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $credential_type = new credentialTypeController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $credential_type_data = new credentialTypeDataController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $reg->setId(( isset( $vars['id'] ) )? $vars['id'] : '0');
        $reg->setName( $this->utils->request_var( 'name', '', 'ALL', true) );
        $reg->setAccount( $this->utils->request_var( 'account', '', 'ALL') );
        $reg->setCredentialType( $this->utils->request_var( 'credential_type', '', 'ALL', true) );
        $reg->setN8NId( $this->utils->request_var( 'n8n_id', '', 'ALL', true) );
        $reg->setN8NName( $this->utils->request_var( 'n8n_name', '', 'ALL', true) );
        $reg->setNotes( $this->utils->request_var( 'notes', '', 'ALL', true) );
        $reg->setActive( $this->utils->request_var( 'active', '1', 'ALL') );

        $data = array(
            'auth_token'    => ( isset( $_POST['auth_token']) )? $_POST['auth_token'] : $this->utils->generateFormToken($form_action),
            'submit'        => ( isset($_POST['btn_submit'])) ? true : false,
            'action'        => ( $reg->getId() == '0' )? 'add' : 'edit',
            'account_options'   => '',
            'credential_type_options'   => '',
            'credential_data'   => $this->utils->request_var( 'credential_data', '', 'ALL', true),
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
                    'section' => $this->lang['CREDENTIAL_EDIT'],
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

            if ( empty( $reg->getAccount() ) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['account'],
                    'msg' => $this->lang['ERR_ACCOUNT_NEEDED'],
                );
            }

            if ( empty( $reg->getCredentialType() ) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['credential_type'],
                    'msg' => $this->lang['ERR_CREDENTIAL_TYPE_NEEDED'],
                );
            }

            if ( empty( $reg->getN8NId() ) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['n8n_id'],
                    'msg' => $this->lang['ERR_CREDENTIAL_N8N_ID_NEEDED'],
                );
            }
            else
            {
                if ( strlen( $reg->getN8NId() ) < 2 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['n8n_id'],
                        'msg' => sprintf( $this->lang['ERR_CREDENTIAL_N8N_ID_SHORT'], '2' ),
                    );
                }
                else if ( strlen( $reg->getN8NId() ) > 100 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['n8n_id'],
                        'msg' => sprintf( $this->lang['ERR_CREDENTIAL_N8N_ID_LONG'], '100' ),
                    );
                }
                else
                {
                    // Check if name already exists
                    if ( $this->db->fetchOne( $reg->getTableName(), 'id', ['n8n_id' => $reg->getN8NId()], ' AND id <> '.$reg->getId()) )
                    {
                        $error_ajax[] = array (
                            'dom_object' => ['n8n_id'],
                            'msg' => $this->lang['ERR_CREDENTIAL_N8N_ID_EXISTS'],
                        );
                    }
                }
            }

            if ( empty( $reg->getN8NName() ) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['n8n_name'],
                    'msg' => $this->lang['ERR_CREDENTIAL_N8N_NAME_NEEDED'],
                );
            }
            else
            {
                if ( strlen( $reg->getN8NName() ) < 2 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['n8n_name'],
                        'msg' => sprintf( $this->lang['ERR_CREDENTIAL_N8N_NAME_SHORT'], '2' ),
                    );
                }
                else if ( strlen( $reg->getN8NName() ) > 100 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['n8n_name'],
                        'msg' => sprintf( $this->lang['ERR_CREDENTIAL_N8N_NAME_LONG'], '100' ),
                    );
                }
                else
                {
                    // Check if name already exists
                    if ( $this->db->fetchOne( $reg->getTableName(), 'id', ['n8n_name' => $reg->getN8NName()], ' AND id <> '.$reg->getId()) )
                    {
                        $error_ajax[] = array (
                            'dom_object' => ['n8n_name'],
                            'msg' => $this->lang['ERR_CREDENTIAL_N8N_NAME_EXISTS'],
                        );
                    }
                }
            }

            foreach ( $data['credential_data'] as $credential_data_key => $credential_data_value )
            {
                if ( empty( $credential_data_value['value'] ) )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['credential_data_field_'.$credential_data_key],
                        'msg' => $this->lang['ERR_CREDENTIAL_DATA_EMPTY'],
                    );
                }
            }

            if ( !sizeof( $error_ajax ) )
            {
                if ( $data['action'] == 'add' )
                {
//$txt = '========== NEW =========='.PHP_EOL; fwrite($this->myfile, $txt);
                    $reg->persistORL();

                    $reg->setCredentialKey( md5( $reg->getId().$reg->getName() ) );
                    $reg->persist();
                }
                else
                {
//$txt = '========== EDIT =========='.PHP_EOL; fwrite($this->myfile, $txt);
                    $reg_original->getRegbyId( $reg->getId() );

                    $reg->setCredentialKey( $reg_original->getCredentialKey() );
                    // name ->from view
                    // account ->from view
                    // credential_type ->from view
                    // n8n_id ->from view
                    // n8n_name ->from view
                    // notes ->from view
                    // active ->from view

                    $reg->persistORL();

                    $reg_data->deleteCredentialData( $reg->getId() );

                    foreach ( $data['credential_data'] as $credential_data_key => $credential_data_value )
                    {
//$txt = 'Credential type data id ========== '.$credential_data_key.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Credential type data value ============ '.$credential_data_value['value'].PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($credential_data_value, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                        $credential_type_data->getRegbyId( $credential_data_key );

                        $reg_data->setId( '' );
                        $reg_data->setName( $credential_type_data->getName() );
                        $reg_data->setFieldName( $credential_type_data->getField() );
                        $reg_data->setFieldValue( $credential_data_value['value'] );
                        $reg_data->persist();
                    }
                }

                // Send success to be displayed
                $response['status'] = 'OK';
                $response['msg'] = $this->lang['CREDENTIAL_SAVED'];
                $response['action'] = '/'.$this->folder.'/'.$this->lang['CREDENTIALS_LINK'];
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
//$txt = '========== NEW =========='.PHP_EOL; fwrite($this->myfile, $txt);
            }
            else
            {
//$txt = '========== EDIT =========='.PHP_EOL; fwrite($this->myfile, $txt);

                if ( $reg->getRegbyId( $reg->getId() ) )
                {
                    $credential_type->getRegbyId( $reg->getCredentialType() );
                    $credential_type_datas = $credential_type_data->getAll( ['credential_type' => $credential_type->getId()] );
//$txt = 'Credential type data =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($credential_type_datas, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

//                    $reg_datas = $reg_data->getAll( ['credential' => $reg->getId()] );
//$txt = 'Credential data =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg_datas, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

                    $credential_datas = array();

                    foreach ( $credential_type_datas as $credential_type_data_key => $credential_type_data_value )
                    {
//$txt = 'Looking for credential data with '.$reg->getId().' / '.$credential_type_data_value['field'].PHP_EOL; fwrite($this->myfile, $txt);
                        $credential_datas[$credential_type_data_value['id']]['field_name'] = $credential_type_data_value['field'];
                        $credential_datas[$credential_type_data_value['id']]['field_value'] =  ( $reg_data->getRegbyCredentialAndFieldName( $reg->getId(), $credential_type_data_value['field'] ) )? $reg_data->getFieldValue() : '';
                    }
//$txt = 'Credential data to display =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($credential_datas, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                }
                else
                {
                    $_SESSION['alert'] = array('type'=>'danger', 'message'=>$this->lang['ERR_CREDENTIAL_NOT_EXISTS']);
                    header('Location: /'.$this->folder.'/'.$this->lang['CREDENTIALS_LINK']);
                    exit;
                }
            }
        }

        // Account select options list
        $filter_select = '';
        $extra_select = 'ORDER BY `name`';
        $data_options_field = 'account_options';
        $account = new accountController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        if ( $reg->getAccount() == '')
        {
            $data[$data_options_field] .= '<option value="0" selected="selected">'.$this->lang['ACCOUNT_SELECT'].'</option>';
            $data[$data_options_field] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        }
        $rows = $account->getAll( $filter_select, $extra_select );
        foreach ( $rows as $row )
        {
            $data[$data_options_field] .= '<option value="'.$row['id'].'"'.(( $reg->getAccount() == $row['id'])? ' selected="selected" ' : '').'>'.$row['name'].' - '.$row['id'].'</option>';
        }

        // Credential type select options list
        $filter_select = '';
        $extra_select = 'ORDER BY `name`';
        $data_options_field = 'credential_type_options';
        $credential_type = new credentialTypeController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        if ( $reg->getCredentialType() == '')
        {
            $data[$data_options_field] .= '<option value="0" selected="selected">'.$this->lang['CREDENTIAL_TYPE_SELECT'].'</option>';
            $data[$data_options_field] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        }
        $rows = $credential_type->getAll( $filter_select, $extra_select );
        foreach ( $rows as $row )
        {
            $data[$data_options_field] .= '<option value="'.$row['id'].'"'.(( $reg->getCredentialType() == $row['id'])? ' selected="selected" ' : '').'>'.$row['name'].' - '.$row['id'].'</option>';
        }

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose($this->myfile);
        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/credentialForm.html.twig', array(
            'reg' => $reg->getReg(),
            'filters' => $this->list_filters,
            'pagination' => $this->pagination,
            'data' => $data,
            'credential_datas' => $credential_datas,
            'cancel' => '/'.$this->folder.'/'.$this->lang['CREDENTIALS_LINK'],
        ));
    }
}