<?php

namespace src\controller\views\app;

use \src\controller\baseViewController;

use \src\controller\entity\bankAccountController;

use DateTime;
use DateTimeZone;

class bankAccountEditViewController extends baseViewController
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
     * @Route('/app/bank_account/edit/id', name='app_bank_account_edit')
     *
     * @param POST
     *
     * @return object    Twig template
     */
    public function edititemAction( $vars )
    {
        $this->logger->info('==============='.__METHOD__.' Bank_account process '.$vars['id'].' | User '.$this->user.' ===================================================');
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/bankAccountEditViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']) );

        $form_action = $this->folder.'/bank_account/editor';

        $this->pagination = $this->utils->request_pagination( $this->pagination );
        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        $reg = new bankAccountController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $bank_account_default = new bankAccountController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $reg->setId(( isset( $vars['id'] ) )? $vars['id'] : '0');
        $reg->setIban( $this->utils->request_var( 'iban', '', 'ALL') );
        $reg->setNumber( $this->utils->request_var( 'number', '', 'ALL') );
        $reg->setName( $this->utils->request_var( 'name', '', 'ALL') );
        $reg->setDefault( $this->utils->request_var( 'default', (( $reg->getId() == '0' )? '1' : '0'), 'ALL') );
        $reg->setActive( $this->utils->request_var( 'active', (( $reg->getId() == '0' )? '1' : '0'), 'ALL') );

        $data = array(
            'auth_token'    => ( isset( $_POST['auth_token']) )? $_POST['auth_token'] : $this->utils->generateFormToken($form_action),
            'submit'        => ( isset($_POST['btn_submit'])) ? true : false,
            'action'        => ( $reg->getId() == '0' )? 'add' : 'edit',
            'iban_options' => '',
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
                    'section' => $this->lang['BANK_ACCOUNT_EDIT'],
                    'alert_type' => 'danger',
                    'title' => $this->lang['WARNING'],
                    'message' => $this->lang['ERR_BAD_TOKEN'],
                    'redirect' => '/logout',
                ));
            }
            */
            
            if ( empty( $reg->getIban() ) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['iban'],
                    'msg' => $this->lang['ERR_BANK_ACCOUNT_IBAN_NEEDED'],
                );
            }
            else
            {
                /*
                // Check if iban already exists
                if ( $this->db->fetchOne( $reg->getTableName(), 'id', ['iban' => $reg->getIban()], ' AND id <> '.$reg->getId()) )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['iban'],
                        'msg' => $this->lang['ERR_BANK_ACCOUNT_IBAN_EXISTS'],
                    );
                }
                */
            }

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

            if ( empty( $reg->getNumber() ) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['number'],
                    'msg' => $this->lang['ERR_BANK_ACCOUNT_NUMBER_NEEDED'],
                );
            }
            else
            {
                /*
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
                    if ( $this->db->fetchOne( $reg->getTableName(), 'id', ['number' => $reg->getNumber()], ' AND id <> '.$reg->getId()) )
                    {
                        $error_ajax[] = array (
                            'dom_object' => ['number'],
                            'msg' => $this->lang['ERR_BANK_ACCOUNT_NUMBER_EXISTS'],
                        );
                    }
                }
                */
            }

            // Check if iban + number already exists
            if ( $this->db->fetchOne( $reg->getTableName(), 'id', ['iban' => $reg->getIban(), 'number' => $reg->getNumber()], ' AND id <> '.$reg->getId()) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['iban'],
                    'msg' => $this->lang['ERR_BANK_ACCOUNT_EXISTS'],
                );
            }

            if ( !sizeof( $error_ajax ) )
            {
                if ( $reg->getDefault() == '1' )
                {
                    $bank_accounts = $reg->getAll();
                    foreach ( $bank_accounts as $key => $bank_account_temp )
                    {
                        $bank_account_default->getRegbyId( $bank_account_temp['id'] );
                        $bank_account_default->setDefault( '0' );
                        $bank_account_default->persist();
                    }
                }

                if ( $data['action'] == 'add' )
                {
                    // new record
                    $reg->persist();

                }
                else
                {
                    // Edit record
                    $reg->persist();
                }

                // Send success to be displayed
                $response['status'] = 'OK';
                $response['msg'] = $this->lang['BANK_ACCOUNT_SAVED'];
                $response['action'] = '/'.$this->folder.'/'.$this->lang['BANK_ACCOUNTS_LINK'];
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
                if( $reg->getRegbyId( $reg->getId() ) )
                {
                }
                else
                {
                    $_SESSION['alert'] = array('type'=>'danger', 'message'=>$this->lang['ERR_BANK_ACCOUNT_NOT_EXISTS']);
                    header('Location: /'.$this->folder.'/'.$this->lang['BANK_ACCOUNTS_LINK']);
                    exit;
                }
            }
        }

        // iban select options list
        require_once(APP_ROOT_PATH.'/src/util/view_selects/iban_all.php');

//$txt = '======================'.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose($this->myfile);
        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/bankAccountForm.html.twig', array(
            'reg' => $reg->getReg(),
            'filters' => $this->list_filters,
            'pagination' => $this->pagination,
            'data' => $data,
            'cancel' => '/'.$this->folder.'/'.$this->lang['BANK_ACCOUNTS_LINK'],
        ));
    }
}