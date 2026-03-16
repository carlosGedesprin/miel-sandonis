<?php

namespace src\controller\views\app;

use \src\controller\baseViewController;
use \src\controller\entity\cronController;

class cronDeleteViewController extends baseViewController
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
     * @Route("/app/cron/delete/id", name="app_cron_delete")
     *
     * @param POST
     *
     * @return object    Twig template
     */
    public function deleteitemAction( $vars )
    {
        $form_action = $this->folder.'/cron/delete';

        $this->pagination = $this->utils->request_pagination( $this->pagination );
        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        if ( !isset( $vars['id']) || $vars['id'] == '' || $vars['id'] == '0' )
        {
            $_SESSION['alert'] = array(
                'type'          => 'danger',
                'message'       => $this->lang['ERR_CRON_PROCESS_NOT_EXISTS'],
                'filters'       => $this->list_filters,
                'pagination'    => $this->pagination,
            );
            header('Location: /'.$this->folder.'/'.$this->lang['CRON_PROCESS_LINK']);
            exit;
        }

        $reg = new cronController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $reg->getRegbyId( $vars['id'] );

        $data = array(
            'auth_token' => ( isset( $_POST['auth_token']) )? $_POST['auth_token'] : $this->utils->generateFormToken($form_action),
            'submit' => (isset($_POST['btn_submit'])) ? true : false,
            'action' => 'delete',
            'run_options' => '',
            'periodicity_options' => '',
            'ordinal_options' => '',
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

            if ( !sizeof( $error_ajax ) )
            {
                $this->logger->info('==============='.__METHOD__.' Cron '.$vars['id'].' | User '.$this->user.' ===================================================');

                $reg->delete();

                $this->pagination['num_page'] = '1';

                $reg->reOrderOrdinals( $reg->getPeriodicity() );

                // Send success to be displayed
                $response['status'] = 'OK';
                $response['msg'] = $this->lang['CRON_DELETED'];
                $response['action'] = '/'.$this->folder.'/'.$this->lang['CRON_PROCESS_LINK'];
                echo json_encode($response);
                exit();
            }
            else
            {
                // Errors found

                // Renew CSRF
                //$data['auth_token'] = $this->utils->generateFormToken($form_action);

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
                // Fields with special treatment
//fwrite($this->myfile, print_r($reg->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                $reg->setLastRun( ( empty($reg->getLastRun()) )? '' : $reg->getLastRun()->format('d-m-Y H:i:s') );
//fwrite($this->myfile, print_r($reg->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

            }
            else
            {
                $_SESSION['alert'] = array(
                    'type'=>'danger',
                    'message'=>$this->lang['ERR_CRON_PROCESS_NOT_EXISTS']
                );
                header('Location: /'.$this->folder.'/'.$this->lang['CRON_PROCESS_LINK']);
                exit();
            }
        }

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

        $data['ordinal_options'] = $reg->getOrdinalOptionsList($data['action'], $reg->getPeriodicity(), $reg->getOrdinal());

        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/cronForm.html.twig', array(
            'reg' => $reg->getReg(),
            'filters' => $this->list_filters,
            'pagination' => $this->pagination,
            'data' => $data,
            'cancel' => '/'.$this->folder.'/'.$this->lang['CRON_PROCESS_LINK'],
        ));
    }
}
