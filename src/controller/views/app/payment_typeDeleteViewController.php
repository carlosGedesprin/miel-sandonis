<?php

namespace src\controller\views\app;

use \src\controller\baseViewController;

use \src\controller\entity\paymentTypeController;

class payment_typeDeleteViewController extends baseViewController
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
     * @Route("/app/payment_type/delete/id", name="app_payment_type_delete")
     *
     * @param POST
     *
     * @return object    Twig template
     */
    public function deleteitemAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/payment_typeControler_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__FUNCTION__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $form_action = $this->folder.'/payment_type/delete';

        $this->pagination = $this->utils->request_pagination( $this->pagination );
        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        if ( !isset( $vars['id']) || $vars['id'] == '' || $vars['id'] == '0' )
        {
            $_SESSION['alert'] = array(
                'type'          => 'danger',
                'message'       => $this->lang['ERR_PAYMENT_TYPE_NOT_EXISTS'],
                'filters'       => $this->list_filters,
                'pagination'    => $this->pagination,
            );
            header('Location: /'.$this->folder.'/'.$this->lang['PAYMENT_TYPES_LINK']);
            exit();
        }

        $reg = new paymentTypeController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $reg->getRegbyId( $vars['id'] );

        $data = array(
            'auth_token' => ( isset( $_POST['auth_token']) )? $_POST['auth_token'] : $this->utils->generateFormToken($form_action),
            'submit' => (isset($_POST['btn_submit'])) ? true : false,
            'action' => 'delete',
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

            if ( $this->db->fetchOne( 'payment', 'id', ['payment_type' => $reg->getId()]) )
            {
                $error_ajax[] = array (
                    'dom_object' => [],
                    'msg' => $this->lang['ERR_PAYMENT_TYPE_HAS_PRODUCTS'],
                );
            }

            if ( !sizeof( $error_ajax ) )
            {
                $this->logger->info('==============='.__METHOD__.' Payment type '.$vars['id'].' | User '.$this->user.' ===================================================');

                $reg->delete();

                $this->pagination['num_page'] = '1';

                $reg->reOrderOrdinals();

                // Send success to be displayed
                $response['status'] = 'OK';
                $response['msg'] = $this->lang['PAYMENT_TYPE_DELETED'];
                $response['action'] = '/'.$this->folder.'/payment_types';
                echo json_encode($response);
                exit();
            }
            else
            {
                // Errors found

                // Renew CSRF
                // $data['auth_token'] = $this->utils->generateFormToken($form_action);
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
            }
            else
            {
                $_SESSION['alert'] = array('type'=>'danger', 'message'=>$this->lang['ERR_PAYMENT_TYPE_NOT_EXISTS']);
                header('Location: /'.$this->folder.'/'.$this->lang['PAYMENT_TYPES_LINK']);
                exit();
            }
        }

        $data['ordinal_options'] = $reg->getOrdinalOptionsList( $data['action'], $reg->getOrdinal() );

        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/payment_typeForm.html.twig', array(
            'reg' => $reg->getReg(),
            'filters' => $this->list_filters,
            'pagination' => $this->pagination,
            'data' => $data,
            'action' => 'delete',
            'cancel' => '/'.$this->folder.'/payment_types',
        ));
    }
}
