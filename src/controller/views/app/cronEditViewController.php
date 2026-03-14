<?php

namespace src\controller\views\app;

use \src\controller\baseViewController;

use \src\controller\entity\cronController;

use DateTime;
use DateTimeZone;

class cronEditViewController extends baseViewController
{
    private $list_filters = array(
                                    'process' => array(
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
     * @Route('/app/cron/edit/id', name='app_cron_edit')
     *
     * @param POST
     *
     * @return object    Twig template
     */
    public function edititemAction( $vars )
    {
        $this->logger->info('==============='.__METHOD__.' Cron process '.$vars['id'].' | User '.$this->user.' ===================================================');
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/cronEditViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__FUNCTION__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']) );

        $form_action = $this->folder.'/cron/editor';

        $this->pagination = $this->utils->request_pagination( $this->pagination );
        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        $reg = new cronController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $reg_original = new cronController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $reg->setId(( isset( $vars['id'] ) )? $vars['id'] : '0');
        $reg->setProcess( $this->utils->request_var( 'process', '', 'ALL') );
        $reg->setRun( $this->utils->request_var( 'run', '0', 'ALL') );
        $reg->setPeriodicity( $this->utils->request_var( 'periodicity', '', 'ALL') );
        $reg->setSize( $this->utils->request_var( 'size', '', 'ALL') );
        $reg->setDelayTime( $this->utils->request_var( 'delaytime', '', 'ALL') );
        $reg->setOrdinal( $this->utils->request_var( 'ordinal', '0', 'ALL') );
        $reg->setLastRun( ($this->utils->request_var( 'last_run', '', 'ALL') == '' )? $now : DateTime::createFromFormat('d-m-Y H:i:s', $this->utils->request_var( 'last_run', '', 'ALL'), new DateTimeZone($this->session->config['time_zone'])) );

        $data = array(
            'auth_token'    => ( isset( $_POST['auth_token']) )? $_POST['auth_token'] : $this->utils->generateFormToken($form_action),
            'submit'        => ( isset($_POST['btn_submit'])) ? true : false,
            'action'        => ( $reg->getId() == '0' )? 'add' : 'edit',
            'run_options'   => '',
            'periodicity_options' => '',
            'ordinal_options' => '',
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
                    'section' => $this->lang['CRON_EDIT'],
                    'alert_type' => 'danger',
                    'title' => $this->lang['WARNING'],
                    'message' => $this->lang['ERR_BAD_TOKEN'],
                    'redirect' => '/logout',
                ));
            }
            */
            
            if ( empty( $reg->getProcess() ) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['process'],
                    'msg' => $this->lang['ERR_CRON_PROCESS_NAME_NEEDED'],
                );
            }
            else
            {
                if ( strlen( $reg->getProcess() ) < 2 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['process'],
                        'msg' => sprintf( $this->lang['ERR_CRON_PROCESS_NAME_SHORT'], '2' ),
                    );
                }
                else if ( strlen( $reg->getProcess() ) > 50 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['process'],
                        'msg' => sprintf( $this->lang['ERR_CRON_PROCESS_NAME_LONG'], '50' ),
                    );
                }
                else
                {
                    // Check if name already exists
                    if ( $this->db->fetchOne( $reg->getTableName(), 'id', ['process' => $reg->getProcess()], ' AND id <> '.$reg->getId()) )
                    {
                        $error_ajax[] = array (
                            'dom_object' => ['process'],
                            'msg' => $this->lang['ERR_CRON_PROCESS_NAME_EXISTS'],
                        );
                    }
                }
            }

            if ( $reg->getRun() == '' )
            {
                $error_ajax[] = array (
                    'dom_object' => ['run'],
                    'msg' => $this->lang['ERR_CRON_PROCESS_RUN_NEEDED'],
                );
            }
            
            if ( empty( $reg->getSize() ) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['size'],
                    'msg' => $this->lang['ERR_CRON_PROCESS_SIZE_NEEDED'],
                );
            }
            else 
            {
                if ( !is_numeric( $reg->getSize() ) )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['size'],
                        'msg' => $this->lang['ERR_CRON_PROCESS_SIZE_NOT_NUMERIC'],
                    ); 
                }
                else
                {
                    if ( $reg->getSize() < 0 || $reg->getSize() > 999 )
                    {
                        $error_ajax[] = array (
                            'dom_object' => ['size'],
                            'msg' => sprintf( $this->lang['ERR_CRON_PROCESS_SIZE_OUT_OF_RANGE'], '999' ),
                        );
                    }
                }
            }

            if ( empty( $reg->getDelaytime() ) ) {
                $error_ajax[] = array (
                    'dom_object' => ['delaytime'],
                    'msg' => $this->lang['ERR_CRON_PROCESS_DELAYTIME_NEEDED'],
                );
            }
            else 
            {
                if ( !is_numeric( $reg->getDelaytime() ) )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['delaytime'],
                        'msg' => $this->lang['ERR_CRON_PROCESS_DELAYTIME_NOT_NUMERIC'],
                    ); 
                }
                else
                {
                    if ( $reg->getDelaytime() < 10 || $reg->getDelaytime() > 9999999999 )
                    {
                        $error_ajax[] = array (
                            'dom_object' => ['delaytime'],
                            'msg' => $this->lang['ERR_CRON_PROCESS_DELAYTIME_OUT_OF_RANGE'],
                        );
                    }
                }
            }
/*
            if ( !empty( $reg->getLast_run() ) )
            {
                if ( strlen( $reg->getLast_run() ) < 2 ) {
                    $error_ajax[] = array (
                        'dom_object' => ['last_run'],
                        'msg' => sprintf( $this->lang['ERR_CRON_PROCESS_LAST_RUN_SHORT'], '2' ),
                    );
                } 
                else if ( strlen( $reg->getLast_run() ) > 15 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['last_run'],
                        'msg' => sprintf( $this->lang['ERR_CRON_PROCESS_LAST_RUN_LONG'], '15' ),
                    );
                }
            }
*/
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

                    if ( $reg->getOrdinal() == 'null' )
                    {
//$txt = 'Ordinal null =========='.PHP_EOL; fwrite($this->myfile, $txt);
                        $reg_original->getRegbyId( $reg->getId() );
//$txt = 'Original REG =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg_original->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                        $reg->setOrdinal( $reg_original->getOrdinal() );
//$txt = 'New Ordinal on REG ======> '.$reg->getOrdinal().PHP_EOL; fwrite($this->myfile, $txt);
                    }

//$txt = 'New REG =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                    $reg->persistORL();
                }

                $reg->reOrderOrdinals( $reg->getPeriodicity() );

                // Send success to be displayed
                $response['status'] = 'OK';
                $response['msg'] = $this->lang['CRON_SAVED'];
                $response['action'] = '/'.$this->folder.'/'.$this->lang['CRON_PROCESS_LINK'];
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
//$txt = 'Reg retrieved =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                }
                else
                {
                    $_SESSION['alert'] = array(
                        'type'=>'danger',
                        'message'=>$this->lang['ERR_CRON_PROCESS_NOT_EXISTS']
                    );
                    header('Location: /'.$this->folder.'/'.$this->lang['CRON_PROCESS_LINK']);
                    exit;
                }
            }
        }

        $reg->setLastRun( ( empty($reg->getLastRun()) )? '' : $reg->getLastRun()->format('d-m-Y H:i:s') );

        if ( $reg->getRun() == '' )
        {
            $data['run_options'] .= '<option value="" selected="selected"><b>' . $this->lang['SELECT'] . '</b></option>';
            $data['run_options'] .= '<option disabled>' . str_repeat('&#x2500', 16) . '</option>';
        }
        $data['run_options'] .= '<option value="0"' . (( $reg->getRun() == '0') ? ' selected="selected" ' : '') . '>'.$this->lang['NO'].'</option>';
        $data['run_options'] .= '<option value="1"' . (( $reg->getRun() == '1') ? ' selected="selected" ' : '') . '>'.$this->lang['YES'].'</option>';

        if ( $reg->getPeriodicity() == '' )
        {
            $data['periodicity_options'] .= '<option value="" selected="selected"><b>' . $this->lang['SELECT'] . '</b></option>';
            $data['periodicity_options'] .= '<option disabled>' . str_repeat('&#x2500', 16) . '</option>';
        }
        $data['periodicity_options'] .= '<option value="minute"' . (( $reg->getPeriodicity() == 'minute') ? ' selected="selected" ' : '') . '>'.$this->lang['CRON_PROCESS_MINUTE'].'</option>';
        $data['periodicity_options'] .= '<option value="hour"' . (( $reg->getPeriodicity() == 'hour') ? ' selected="selected" ' : '') . '>'.$this->lang['CRON_PROCESS_HOUR'].'</option>';
        $data['periodicity_options'] .= '<option value="day"' . (( $reg->getPeriodicity() == 'day') ? ' selected="selected" ' : '') . '>'.$this->lang['CRON_PROCESS_DAY'].'</option>';
        $data['periodicity_options'] .= '<option value="webcron"' . (( $reg->getPeriodicity() == 'webcron') ? ' selected="selected" ' : '') . '>'.$this->lang['CRON_PROCESS_WEBCRON'].'</option>';

        if ( $data['action'] != 'add' )
        {
            $data['ordinal_options'] = $reg->getOrdinalOptionsList($data['action'], $reg->getPeriodicity(), $reg->getOrdinal());
        }

//$txt = 'cronEditViewController edititemAction end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose($this->myfile);
        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/cronForm.html.twig', array(
            'reg' => $reg->getReg(),
            'filters' => $this->list_filters,
            'pagination' => $this->pagination,
            'data' => $data,
            'cancel' => '/'.$this->folder.'/'.$this->lang['CRON_PROCESS_LINK'],
        ));
    }
}