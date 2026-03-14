<?php

namespace src\controller\views\app;

use DateTime;
use DateTimeZone;
use \src\controller\baseViewController;

use \src\controller\entity\automationController;

class automationDeleteViewController extends baseViewController
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
     * @Route("/app/automation/delete/id", name="app_automation_delete")
     *
     * @param POST
     *
     * @return object    Twig template
     */
    public function deleteitemAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/automationDeleteViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $form_action = $this->folder.'/automation/delete';

        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']) );

        $this->pagination = $this->utils->request_pagination( $this->pagination );
        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        if ( !isset( $vars['id']) || $vars['id'] == '' || $vars['id'] == '0' )
        {
            $_SESSION['alert'] = array(
                'type'          => 'danger',
                'message'       => $this->lang['ERR_AUTOMATION_NOT_EXISTS'],
                'filters'       => $this->list_filters,
                'pagination'    => $this->pagination,
            );
            header('Location: /'.$this->folder.'/'.$this->lang['AUTOMATIONS_LINK']);
            exit;
        }

        $reg = new automationController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $reg->getRegbyId( $vars['id'] );

        $data = array(
            'auth_token' => ( isset( $_POST['auth_token']) )? $_POST['auth_token'] : $this->utils->generateFormToken($form_action),
            'submit' => (isset($_POST['btn_submit'])) ? true : false,
            'action' => 'delete',
            'account_options' => '',
            'billing_account_options' => '',
            'product_setup_options' => '',
            'product_renewal_options' => '',
            'agent_options' => '',
            'coupon_options' => '',
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

            if ( !empty( $reg->getRag() ) )
            {
                $error_ajax[] = array(
                    'dom_object' => ['rag'],
                    'msg' => $this->lang['ERR_AUTOMATION_HAS_RAG'],
                );
            }

            if ( !sizeof( $error_ajax ) )
            {
                $this->logger->info('==============='.__METHOD__.' Automation '.$vars['id'].' | User '.$this->user.' ===================================================');

                $reg->delete();

                $this->pagination['num_page'] = '1';

                // Send success to be displayed
                $response['status'] = 'OK';
                $response['msg'] = $this->lang['AUTOMATION_DELETED'];
                $response['action'] = '/'.$this->folder.'/'.$this->lang['AUTOMATIONS_LINK'];
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
//$txt = 'Response =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($response, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
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

                // Field with special treatment
                $reg->setDateReg( ( $reg->getDateReg() == '' )? NULL : $reg->getDateReg()->format('d-m-Y H:i:s') );
                $reg->setDateStart( ( $reg->getDateStart() == '' )? NULL : $reg->getDateStart()->format('d-m-Y') );
                $reg->setDateEnd( ( $reg->getDateEnd() == '' )? NULL : $reg->getDateEnd()->format('d-m-Y') );

            }
            else
            {
                $_SESSION['alert'] = array('type'=>'danger', 'message'=>$this->lang['ERR_AUTOMATION_NOT_EXISTS']);
                header('Location: /'.$this->folder.'/'.$this->lang['AUTOMATIONS_LINK']);
                exit();
            }
        }

        // account select options list
        require_once(APP_ROOT_PATH . '/src/util/view_selects/account_account.php');

        // billing account select options list
        require_once(APP_ROOT_PATH . '/src/util/view_selects/account_billing_account.php');

        // product setup select options list
        require_once(APP_ROOT_PATH . '/src/util/view_selects/product_automation_setup.php');

        // product select options list
        require_once(APP_ROOT_PATH . '/src/util/view_selects/product_automation_renewal.php');

        // coupon select options list
        require_once(APP_ROOT_PATH.'/src/util/view_selects/coupon.php');

        // agent options
        require_once(APP_ROOT_PATH.'/src/util/view_selects/account_agent.php');

//$txt = '======================'.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose($this->myfile);
        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/automationForm.html.twig', array(
            'reg' => $reg->getReg(),
            'filters' => $this->list_filters,
            'pagination' => $this->pagination,
            'data' => $data,
            'cancel' => '/'.$this->folder.'/'.$this->lang['AUTOMATIONS_LINK'],
        ));
    }
}
