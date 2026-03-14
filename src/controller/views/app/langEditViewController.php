<?php

namespace src\controller\views\app;

use \src\controller\baseViewController;

use \src\controller\entity\langController;
use \src\controller\entity\langNameController;

class langEditViewController extends baseViewController
{

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
     * @Route('/app/lang/edit/id', name='app_lang_edit')
     *
     * @param POST
     *
     * @return object    Twig template
     */
    public function edititemAction( $vars )
    {
         $this->logger->info('==============='.__METHOD__.' Lang '.$vars['id'].' | User '.$this->user.' ===================================================');

//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/langEditViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'POST =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Vars =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($vars, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $form_action = $this->folder.'/lang/editor';

        $this->pagination = $this->utils->request_pagination( $this->pagination );
        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        $reg = new langController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $reg->setId( ( isset( $vars['id'] ) )? $vars['id'] : '0' );
        $reg->setCode2a( $this->utils->request_var( 'code_2a', '', 'ALL') );
        $reg->setCode3a( $this->utils->request_var( 'code_3a', '', 'ALL') );
        $reg->setFamily( $this->utils->request_var( 'family', '', 'ALL', true) );
        $reg->setIsoName( $this->utils->request_var( 'iso_name', '', 'ALL') );
        $reg->setFolder( $this->utils->request_var( 'folder', '', 'ALL') );
        $reg->setDefault( $this->utils->request_var( 'default', '0', 'ALL') );
        $reg->setActive( $this->utils->request_var( 'active', '0', 'ALL') );

        $reg_name = new langNameController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        
        $reg_name->setLangCode2a( $reg->getCode2a() );
        
        $data = array(
                        'auth_token' => ( isset( $_POST['auth_token']) )? $_POST['auth_token'] : $this->utils->generateFormToken($form_action),
                        'submit' => (isset($_POST['btn_submit'])) ? true : false,
                        'action' => ( $reg->getId() == '0' )? 'add' : 'edit',
                        'default_ini' => '',
                        'active_options' => '',
                        //********* Names treatment start ******************
                        'names' => array(),
                        //********* Names treatment end ******************
        );
//$txt = 'Reg =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Data =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $error_ajax = array();

        //********* Names treatment start ******************
//$txt = 'Names from form =====================>'.PHP_EOL; fwrite($this->myfile, $txt);
        $rows = $reg->getActiveAndPreActive();
        // This language in his language
        if ( $reg->getCode2a() )
        {
            if ( !isset( $data['names'][$reg->getCode2a()][$reg->getCode2a()] ) )
            {
                $rows[] = array(
                                'code_2a' => $reg->getCode2a(),
                            );
            }
        }
//$txt = 'Active & Preactive langs =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($rows, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        foreach ( $rows as $row )
        {
            $data['names'][$row['code_2a']]['lang_code_2a'] = $row['code_2a'];
            $data['names'][$row['code_2a']]['lang_name'] = $this->utils->request_var_array( 'lang_name', $row['code_2a'], '', 'POST', true);
        }
//$txt = 'Data names from form =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($data['names'], TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        //********* Names treatment end ******************

        if ( $data['submit'] )
        {
//            if ( empty( $lang_text->getContext() ) )
//            {
//                $error_ajax[] = array (
//                    'dom_object' => ['context'],
//                    'msg' => $this->lang['ERR_LANG_TEXT_CONTEXT_NEEDED'],
//                );
//            }

            if ( !sizeof( $error_ajax ) )
            {
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

                //********* Names treatment start ******************
//$txt = 'Names to table =====================>'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Deleting names in table =========='.PHP_EOL; fwrite($this->myfile, $txt);
                $reg_name->deleteByLangCode2a( $reg->getCode2a() );

//$txt = 'Names from form =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($data['names'], TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

//$txt = 'Saving to names table =========='.PHP_EOL; fwrite($this->myfile, $txt);
                $rows = $reg->getActiveAndPreActive();
                // This language in his language
                if ( $reg->getCode2a() )
                {
                    if ( !isset( $data['names'][$reg->getCode2a()][$reg->getCode2a()] ) )
                    {
                        $rows[] = array(
                            'code_2a' => $reg->getCode2a(),
                        );
                    }
                }
//$txt = 'Langs active and pre-active + this lang =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($rows, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                foreach ( $rows as $row )
                {
//$txt = 'Lang to treat ====> ('.$row['code_2a'].')'.PHP_EOL; fwrite($this->myfile, $txt);
                    $reg_name->setId( NULL );
                    $reg_name->setLangCode2a( $reg->getCode2a() );
                    $reg_name->setLang2a( $row['code_2a'] );
                    $reg_name->setName( $data['names'][$row['code_2a']]['lang_name'] );
//fwrite($this->myfile, print_r($reg_name->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                    if ( $data['names'][$row['code_2a']]['lang_name'] != '' ) $reg_name->persist();
                }
                //********* Names treatment end ******************

                // Send success to be displayed
                $response['status'] = 'OK';
                $response['msg'] = $this->lang['LANG_SAVED'];
                $response['action'] = '/'.$this->folder.'/langs';
                echo json_encode($response);
                exit();
            }
            else
            {
                // Errors found
//$txt = 'Errors after verifications =========='.PHP_EOL; fwrite($this->myfile, $txt);

                // Renew CSRF
                //$data['auth_token'] = $this->utils->generateFormToken($form_action);

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
                $reg->getRegbyId( $reg->getId() );
//$txt = 'Reg =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
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
        }

        // active select options list
        if ( $reg->getActive() == '' )
        {
            $data['active_options'] .= '<option value="" selected="selected"><b>' . $this->lang['SELECT'] . '</b></option>';
            $data['active_options'] .= '<option disabled>' . str_repeat('&#x2500', 16) . '</option>';
        }
        $data['active_options'] .= '<option value="0"' . (($reg->getActive() == '0') ? ' selected="selected" ' : '') . '>'.$this->lang['NO'].'</option>';
        $data['active_options'] .= '<option value="1"' . (($reg->getActive() == '1') ? ' selected="selected" ' : '') . '>'.$this->lang['YES'].'</option>';
        $data['active_options'] .= '<option value="2"' . (($reg->getActive() == '2') ? ' selected="selected" ' : '') . '>'.$this->lang['PRE_ACTIVE'].'</option>';

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
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
