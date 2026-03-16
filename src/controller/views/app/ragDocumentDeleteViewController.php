<?php

namespace src\controller\views\app;

use \src\controller\baseViewController;

use \src\controller\entity\ragController;
use \src\controller\entity\ragDocumentController;

class ragDocumentDeleteViewController extends baseViewController
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
                                'rag' => array(
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
     * @Route('/app/rag-document/delete/id', name='app_rag_document_delete')
     *
     * @param POST
     *
     * @return object    Twig template
     */
    public function deleteitemAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/ragDocumentDeleteViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Vars =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($vars, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $form_action = $this->folder.'/rag_document/delete';

        $this->pagination = $this->utils->request_pagination( $this->pagination );
        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        if ( !isset( $vars['id']) || $vars['id'] == '' || $vars['id'] == '0' )
        {
            $_SESSION['alert'] = array(
                'type'          => 'danger',
                'message'       => $this->lang['ERR_RAG_DOCUMENT_NOT_EXISTS'],
                'filters'       => $this->list_filters,
                'pagination'    => $this->pagination,
            );
            header('Location: /'.$this->folder.'/'.$this->lang['RAG_DOCUMENTS_LINK']);
            exit();
        }

        $reg = new ragDocumentController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $rag = new ragController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $reg->getRegbyId( $vars['id'] );

        $data = array(
            'auth_token' => ( isset( $_POST['auth_token']) )? $_POST['auth_token'] : $this->utils->generateFormToken($form_action),
            'submit' => (isset($_POST['btn_submit'])) ? true : false,
            'action' => 'delete',
            'upload_max_file_size' => $this->session->config['max_size_file_upload'],
            'rag_options' => '',
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

            if ( $reg->getStatus() == '0' )
            {
                $error_ajax[] = array (
                    'dom_object' => ['status_div'],
                    'msg' => $this->lang['ERR_RAG_DOCUMENT_IN_PROCESS'],
                );
            }

            if ( !$rag->getRegbyId( $reg->getRag() ) )
            {
                $error_ajax[] = array(
                    'dom_object' => ['rag'],
                    'msg' => $this->lang['ERR_RAG_NOT_EXISTS'],
                );
            }
            else
            {
                $ftp_conn = @ftp_ssl_connect( $rag->getAddress() );
                //$ftp_conn = ftp_connect( $rag->getAddress() );
                if ( !$ftp_conn )
                {
                    $error_ajax[] = array(
                        'dom_object' => ['rag'],
                        'msg' => $this->lang['ERR_RAG_BAD_CREDENTIALS'],
                    );
                }
                else
                {
                    if ( @ftp_login( $ftp_conn, $rag->getUsername(), $rag->getPassword() ) )
                    {
//$txt = 'FTP Connection established.'.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
                        @ftp_pasv( $ftp_conn, true );
                    }
                    else
                    {
                        @ftp_close( $ftp_conn );
                        $error_ajax[] = array(
                            'dom_object' => ['rag'],
                            'msg' => $this->lang['ERR_RAG_BAD_CREDENTIALS'],
                        );
                    }
                }
            }

            if ( !sizeof( $error_ajax ) )
            {
                $this->logger->info('==============='.__METHOD__.' Rag document '.$vars['id'].' | User '.$this->user.' ===================================================');

                $file_on_ftp = ( ( !empty( $rag->getFolder() ) )? $rag->getFolder().'/' : '' ).$reg->getFileName().'.'.$reg->getExtension();

                if ( @ftp_delete( $ftp_conn, $file_on_ftp ) )
                {
                    $reg->delete();

                    $this->pagination['num_page'] = '1';

                    // Send success to be displayed
                    $response['status'] = 'OK';
                    $response['msg'] = $this->lang['RAG_DOCUMENT_DELETED'];
                    $response['action'] = '/'.$this->folder.'/'.$this->lang['RAG_DOCUMENTS_LINK'];
                    echo json_encode($response);
                    exit();

                }
                else
                {
                    $_SESSION['alert'] = array(
                        'type'          => 'danger',
                        'message'       => $this->lang['ERR_RAG_DOCUMENT_NOT_DELETED'],
                        'filters'       => $this->list_filters,
                        'pagination'    => $this->pagination,
                    );
                    header('Location: /'.$this->folder.'/'.$this->lang['RAG_DOCUMENTS_LINK']);
                    exit();
                }
            }
            else
            {
                // Errors found

                // Renew CSRF
//                $data['auth_token'] = $this->utils->generateFormToken($form_action);
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
                // Field with special treatment
                $reg->setDateReg( ( $reg->getDateReg() == '' )? NULL : $reg->getDateReg()->format('d-m-Y H:i:s') );
            }
            else
            {
                $_SESSION['alert'] = array(
                                            'type'          => 'danger',
                                            'message'       => $this->lang['ERR_RAG_DOCUMENT_NOT_EXISTS'],
                                            'filters'       => $this->list_filters,
                                            'pagination'    => $this->pagination,
                );
                header('Location: /'.$this->folder.'/'.$this->lang['RAG_DOCUMENTS_LINK']);
                exit();
            }
        }

        // Rag options
        if ( empty( $reg->getRag() ) )
        {
            $data['rag_options'] .= '<option value="" selected="selected">'.$this->lang['RAG_SELECT'].'</option>';
            $data['rag_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        }
        $filter_select = array(
            'active' => '1'
        );
        $extra_select = 'ORDER BY `name`';
        $rag = new ragController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $rows = $rag->getAll( $filter_select, $extra_select);
        foreach ( $rows as $row)
        {
            $data['rag_options'] .= '<option value="'.$row['id'].'"'.(($reg->getRag() == $row['id'])? ' selected="selected" ' : '').'>'.$row['name'].'</option>';
        }

        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/ragDocumentForm.html.twig', array(
            'reg' => $reg->getReg(),
            'filters' => $this->list_filters,
            'pagination' => $this->pagination,
            'data' => $data,
            'cancel' => '/'.$this->folder.'/'.$this->lang['RAG_DOCUMENTS_LINK'],
        ));
    }
}
