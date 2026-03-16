<?php

namespace src\controller\views\app;

use \src\controller\baseViewController;

use \src\controller\entity\langTextController;
use \src\controller\entity\langTextNameController;
use \src\controller\entity\langController;
use \src\controller\entity\langNameController;

use \src\controller\entity\userController;

class langTextEditViewController extends baseViewController
{
    private $list_filters = array(
                                    'lang_key' => array(
                                        'type' => 'text',
                                        'caption' => '',
                                        'placeholder' => '',
                                        'width' => '0',	// if 0 uses the rest of the row
                                        'value' => '',
                                        'value_previous' => '',
                                    ),
                                    'context' => array(
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
                                'order'          => 'lang_key',
                                'order_dir'      => 'ASC',
                                'rpp'            => '',
    );

    private $folder = 'app';

    /**
     * @Route('/app/langtext/edit/id', name='app_langtext_edit')
     *
     * @param POST
     *
     * @return object    Twig template
     */
    public function edititemAction( $vars )
    {
         $this->logger->info('==============='.__METHOD__.' Lang text '.$vars['id'].' | User '.$this->user.' ===================================================');
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/langTextEditViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'POST =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Vars =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($vars, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $form_action = $this->folder.'/langtext/editor';

        $this->pagination = $this->utils->request_pagination( $this->pagination );
        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        $lang_text = new langTextController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $lang_text_name = new langTextNameController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $lang = new langController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $lang_name = new langNameController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $user = new userController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $lang_text->setId( ( isset( $vars['id'] ) )? $vars['id'] : '0' );
        $lang_text->setContext( $this->utils->request_var( 'context', '', 'ALL') );
        $lang_text->setLangKey( $this->utils->request_var( 'lang_key', '', 'ALL') );

        $data = array(
                        'auth_token' => ( isset( $_POST['auth_token']) )? $_POST['auth_token'] : $this->utils->generateFormToken($form_action),
                        'submit' => (isset($_POST['btn_submit'])) ? true : false,
                        'action' => ( $lang_text->getId() == '0' )? 'add' : 'edit',
                        'default_ini' => '',
                        'default_options' => '',
                        'context_options' => '',
                        'active_options' => '',
                        //********* Names treatment start ******************
                        'texts' => $this->utils->request_var( 'texts', '', 'ALL', true),
                        'names' => array(),
                        //********* Names treatment end ******************
        );

        $error_ajax = array();

//$txt = 'POST =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Vars =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($vars, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Reg =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($lang_text->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Data =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        //********* Names treatment start ******************
//$txt = 'Text names from form =====================>'.PHP_EOL; fwrite($this->myfile, $txt);
        $langs = $lang->getActiveAndPreActive();
//$txt = 'Active & Preactive langs =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($langs, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        foreach ( $langs as $lang_temp )
        {
            $data['names'][$lang_temp['code_2a']]['lang_code_2a'] = $lang_temp['code_2a'];
            $data['names'][$lang_temp['code_2a']]['lang_name'] = $lang_temp['iso_name'];
            //$data['names'][$lang_temp['code_2a']]['text'] = $this->utils->request_var_array( 'texts', $lang_temp['code_2a'], '', 'ALL', true );
            $data['names'][$lang_temp['code_2a']]['text'] = ( isset( $data['texts'][$lang_temp['code_2a']] ) )? $data['texts'][$lang_temp['code_2a']] : '';
        }
//$txt = 'Text names from form first =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($data['names'], TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        //********* Names treatment end ******************

        if ( $data['submit'] )
        {
            if ( empty( $lang_text->getContext() ) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['context'],
                    'msg' => $this->lang['ERR_LANG_TEXT_CONTEXT_NEEDED'],
                );
            }

            if ( empty( $lang_text->getLangKey() ) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['lang_key'],
                    'msg' => $this->lang['ERR_LANG_TEXT_KEY_NEEDED'],
                );
            }
            else
            {
                if ( strlen( $lang_text->getLangKey() ) < 2 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['lang_key'],
                        'msg' => sprintf( $this->lang['ERR_LANG_TEXT_KEY_SHORT'], '2' ),
                    );
                }
                else if ( strlen( $lang_text->getLangKey() ) > 100 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['lang_key'],
                        'msg' => sprintf( $this->lang['ERR_LANG_TEXT_KEY_LONG'], '100' ),
                    );
                }
                else
                {
                    if ( $lang_text->langTextWithSameLangKey( $lang_text->getLangKey(), 'id', $lang_text->getId() ) )
                    {
                        $error_ajax[] = array (
                            'dom_object' => ['lang_key'],
                            'msg' => $this->lang['ERR_LANG_TEXT_KEY_EXISTS'],
                        );
                    }
                }
            }

            //********* Names treatment start ******************
            $user->getRegbyId( $this->user );
//$txt = 'Names from form =====================>'.PHP_EOL; fwrite($this->myfile, $txt);
            $rows = $lang->getActiveAndPreActive();
//$txt = 'Langs active and pre-active + this lang =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($rows, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
            foreach ( $rows as $row ) {
//$txt = 'Lang to treat ====> ('.$row['code_2a'].') text ('.$data['names'][$row['code_2a']]['text'].')'.PHP_EOL; fwrite($this->myfile, $txt);
                if ( $data['names'][$row['code_2a']]['text'] == '' )
                {
//$txt = 'Lang text name missing in lang '.$row['id'].PHP_EOL; fwrite($this->myfile, $txt);
                    $lang->getRegbyId( $row['id'] );
//$txt = 'user locale '.$this->user.' locale '.$user->getLocale().PHP_EOL; fwrite($this->myfile, $txt);
                    $lang_name->getRegbyCodeAndLang( $row['code_2a'], $user->getLocale() );

                    $error_ajax[] = array (
                        'dom_object' => ['texts_'.$row['code_2a']],
                        'msg' => str_replace('%locale%', $lang_name->getName(), $this->lang['ERR_LANG_TEXT_NAME_NEEDED'] ),
                    );
                }
            }
            //********* Names treatment end ******************

            if ( !sizeof( $error_ajax ) )
            {
                if ( $data['action'] == 'add' )
                {
//$txt = '========== NEW =========='.PHP_EOL; fwrite($this->myfile, $txt);
                    $lang_text->persist();
                }
                else
                {
//$txt = '========== EDIT =========='.PHP_EOL; fwrite($this->myfile, $txt);
                    $lang_text->persist();
                }

                //********* Names treatment start ******************
//$txt = 'Names to api =====================>'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Deleting names in table =========='.PHP_EOL; fwrite($this->myfile, $txt);
                $lang_text_name->deleteByLangText( $lang_text->getId() );

//$txt = 'Names from form =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($data['names'], TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

//$txt = 'Sending to names to api =========='.PHP_EOL; fwrite($this->myfile, $txt);
                $rows = $lang->getActiveAndPreActive();
//$txt = 'Langs active and pre-active + this lang =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($rows, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                foreach ( $rows as $row )
                {
//$txt = 'Lang to treat ====> ('.$row['code_2a'].')'.PHP_EOL; fwrite($this->myfile, $txt);
                    if ( $data['names'][$row['code_2a']]['text'] != '' )
                    {
                        $lang_text_name->setId( NULL );
                        $lang_text_name->setLangText( $lang_text->getId() );
                        $lang_text_name->setLangCode2a( $row['code_2a'] );
                        $lang_text_name->setText( $data['names'][$row['code_2a']]['text'] );
//$txt = 'Lang text name before send to api ====> '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($lang_text_name->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                        $lang_text_name->persist();
                    }
                }
                //********* Names treatment end ******************

                // Send success to be displayed
                $response['status'] = 'OK';
                $response['msg'] = $this->lang['LANG_SAVED'];
                $response['action'] = '/'.$this->folder.'/langtexts';
                echo json_encode($response);
                exit();
            }
            else
            {
                // Errors found

                // Renew CSRF - It gives issues with ajax and session destroy in startup
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
//$txt = '========== Not submit NEW =========='.PHP_EOL; fwrite($this->myfile, $txt);
            }
            else
            {
//$txt = '========== Not submit EDIT =========='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Reg id vars ('.$vars['id'].') reg ('.$lang_text->getId().')'.PHP_EOL; fwrite($this->myfile, $txt);
                $lang_text->getRegbyId( $lang_text->getId() );
//$txt = 'Reg =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($lang_text->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
            }

            //********* Names treatment start ******************
//$txt = 'Text names from api ==================================>'.PHP_EOL; fwrite($this->myfile, $txt);
            $langs = $lang->getActiveAndPreActive();
//$txt = 'Langs active and pre-active =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($langs, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
            foreach ( $langs as $lang_temp )
            {
//$txt = 'Lang key to treat ('.$lang_text->getLangKey().') Lang to treat ('.$lang_temp['code_2a'].') '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($lang_temp, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                //$data['names'][$lang_temp['code_2a']]['lang_code_2a'] = $lang_temp['code_2a'];
                $lang_text_name->getRegbyLangTextAndLang( $lang_text->getId(), $lang_temp['code_2a'] );
//$txt = 'Lang name of '.$lang_text->getId().' -- '.$lang_text->getLangKey().' -- ('.$lang_text_name->getText().')'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($lang_text_name->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

//$txt = 'Lang code 2a '.$lang_temp['code_2a'].' text '.$lang_text_name->getText().PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
                $data['names'][$lang_temp['code_2a']]['text'] = $lang_text_name->getText();
//$txt = 'Data name populated '.$data['names'][$lang_temp['code_2a']]['text'].PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
            }
//$txt = 'Names in data names =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($data['names'], TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
            //********* Names treatment end ******************
        }

        // context select options list
        if ( $lang_text->getContext() == '' )
        {
            $data['context_options'] .= '<option value="" selected="selected">' . $this->lang['SELECT'] . '</option>';
            $data['context_options'] .= '<option disabled>' . str_repeat('&#x2500', 16) . '</option>';
        }
        $data['context_options'] .= '<option value="general"' . (( $lang_text->getContext() == 'general') ? ' selected="selected" ' : '') . '>'.'general'.'</option>';
        $data['context_options'] .= '<option value="app"' . (( $lang_text->getContext() == 'app') ? ' selected="selected" ' : '') . '>'.'app'.'</option>';
        $data['context_options'] .= '<option value="web"' . ((  $lang_text->getContext() == 'web') ? ' selected="selected" ' : '') . '>'.'web'.'</option>';
        $data['context_options'] .= '<option value="errors"' . ((  $lang_text->getContext() == 'errors') ? ' selected="selected" ' : '') . '>'.'errors'.'</option>';
        $data['context_options'] .= '<option value="security"' . ((  $lang_text->getContext() == 'security') ? ' selected="selected" ' : '') . '>'.'security'.'</option>';
        $data['context_options'] .= '<option value="legal"' . ((  $lang_text->getContext() == 'legal') ? ' selected="selected" ' : '') . '>'.'legal'.'</option>';

//$txt = 'Reg to display =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($lang_text->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose($this->myfile);
        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/langtextForm.html.twig', array(
            'reg' => $lang_text->getReg(),
            'filters' => $this->list_filters,
            'pagination' => $this->pagination,
            'data' => $data,
            'cancel' => '/'.$this->folder.'/langtexts',
        ));
    }
}
