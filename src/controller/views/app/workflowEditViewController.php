<?php

namespace src\controller\views\app;

use \src\controller\baseViewController;

use \src\controller\entity\workflowController;
use \src\controller\entity\credentialController;

use DateTime;
use DateTimeZone;

class workflowEditViewController extends baseViewController
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
     * @Route('/app/workflow/edit/id', name='app_workflow_edit')
     *
     * @param POST
     *
     * @return object    Twig template
     */
    public function edititemAction( $vars )
    {
        $this->logger->info('==============='.__METHOD__.' Workflow process '.$vars['id'].' | User '.$this->user.' ===================================================');
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/workflowEditViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']) );

        $form_action = $this->folder.'/workflow/editor';

        $this->pagination = $this->utils->request_pagination( $this->pagination );
        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        $reg = new workflowController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $reg_original = new workflowController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $credential = new credentialController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $reg->setId(( isset( $vars['id'] ) )? $vars['id'] : '0');
        $reg->setName( $this->utils->request_var( 'name', '', 'ALL') );
        $reg->setJson( $this->utils->request_var( 'json', '', 'ALL') );
        $reg->setActive( $this->utils->request_var( 'active', '1', 'ALL') );

        $data = array(
            'auth_token'    => ( isset( $_POST['auth_token']) )? $_POST['auth_token'] : $this->utils->generateFormToken($form_action),
            'submit'        => ( isset($_POST['btn_submit'])) ? true : false,
            'action'        => ( $reg->getId() == '0' )? 'add' : 'edit',
            'credentials'     => $this->utils->request_var( 'credentials', '', 'ALL'),
            'credentials_options' => '',
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
                    'section' => $this->lang['WORKFLOW_EDIT'],
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

            if ( empty( $reg->getJson() ) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['json'],
                    'msg' => $this->lang['ERR_WORKFLOW_JSON_NEEDED'],
                );
            }
            else
            {
                if ( strlen( $reg->getJson() ) < 2 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['JSON'],
                        'msg' => sprintf( $this->lang['ERR_WORKFLOW_JSON_SHORT'], '2' ),
                    );
                }
            }

            if ( !sizeof( $error_ajax ) )
            {
                if ( $data['action'] == 'add' )
                {
//$txt = '========== NEW =========='.PHP_EOL; fwrite($this->myfile, $txt);
                    $reg->persistORL();
                }
                else
                {
//$txt = '========== EDIT =========='.PHP_EOL; fwrite($this->myfile, $txt);
                    $reg_original->getRegbyId( $reg->getId() );

                    $reg->setKey( $reg_original->getKey() );
                    // name ->from view
                    // JSON ->from view

                    $reg->persistORL();
                }

//$txt = 'Credentials ======= '.$data['credentials'].PHP_EOL; fwrite($this->myfile, $txt);
                $data['credentials'] = explode(",", $data['credentials']);
//$txt = 'Credentials array ======= '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($data['credentials'], TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                $reg->cleanCredentials();
                foreach ( $data['credentials'] as $credential_value )
                {
//$txt = 'Credential to treat ======= '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($credential_value, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                    $credential->getRegbyId( $credential_value );

                    $reg->setCredential( $credential->getId() );
                }

                $reg->persist();

                // Send success to be displayed
                $response['status'] = 'OK';
                $response['msg'] = $this->lang['WORKFLOW_SAVED'];
                $response['action'] = '/'.$this->folder.'/'.$this->lang['WORKFLOWS_LINK'];
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
//$txt = '========== Not submit =========='.PHP_EOL; fwrite($this->myfile, $txt);
            $credentials = array();

            if ( $data['action'] == 'add' )
            {
//$txt = '========== NEW =========='.PHP_EOL; fwrite($this->myfile, $txt);
            }
            else
            {
//$txt = '========== EDIT =========='.PHP_EOL; fwrite($this->myfile, $txt);

                if( $reg->getRegbyId( $reg->getId() ) )
                {
                }
                else
                {
                    $_SESSION['alert'] = array('type'=>'danger', 'message'=>$this->lang['ERR_WORKFLOW_NOT_EXISTS']);
                    header('Location: /'.$this->folder.'/'.$this->lang['WORKFLOWS_LINK']);
                    exit;
                }
            }
        }

        // Credentials select options list
        $filter_select = [
                            'active' => '1'
        ];
        $extra_select = 'ORDER BY `name`';
        $data_options_field = 'credentials_options';
        $credential = new credentialController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $rows = $credential->getAll( $filter_select, $extra_select );
        if ( empty( $reg->getCredentials() ) )
        {
            $data[$data_options_field] .= '<option value="">'.$this->lang['SELECT'].'</option>';
            $data[$data_options_field] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        }
        foreach ( $rows as $row )
        {
            $credential->getRegbyId( $row['id'] );

            $data['credentials_options'] .= '<option value="' . $credential->getId() . '"';

            if ( in_array( $credential->getId(), $reg->getCredentials() ) )
            {
                $data['credentials_options'] .= ' selected="selected" ';
            }

            $data['credentials_options'] .= '>' . $credential->getName() . '</option>';
        }

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose($this->myfile);
        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/workflowForm.html.twig', array(
            'reg' => $reg->getReg(),
            'filters' => $this->list_filters,
            'pagination' => $this->pagination,
            'data' => $data,
            'cancel' => '/'.$this->folder.'/'.$this->lang['WORKFLOWS_LINK'],
        ));
    }
}