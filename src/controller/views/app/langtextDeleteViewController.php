<?php

namespace src\controller\views\app;

use \src\controller\baseViewController;

use \src\controller\entity\langController;
use \src\controller\entity\langTextController;
use \src\controller\entity\langTextNameController;

class langtextDeleteViewController extends baseViewController
{
    private $table = 'lang_text';
    private $table_name = 'lang_text_name';

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
     * @Route('/app/langtext/delete/id', name='app_lang_text_delete')
     *
     * @param POST
     *
     * @return object    Twig template
     */
    public function deleteitemAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/langtextDeleteViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Lang text to delete '.$vars['id'].PHP_EOL; fwrite($this->myfile, $txt);

        $form_action = $this->folder.'/lang_text/delete';

        $this->pagination = $this->utils->request_pagination( $this->pagination );
        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        $lang_text = new langTextController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $lang_text_name = new langTextNameController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $lang = new langController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $lang_text->getRegbyId( ( isset( $vars['id'] ) )? $vars['id'] : '0' );

        $data = array(
            'auth_token' => ( isset( $_POST['auth_token']) )? $_POST['auth_token'] : $this->utils->generateFormToken($form_action),
            'submit' => (isset($_POST['btn_submit'])) ? true : false,
            'action' => 'delete',
            'context_options' => '',
            'texts' => '',
        );
//$txt = 'On start function start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'POST =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'REG =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($lang_text, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Data =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'On start function end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        if ( !isset( $vars['id']) || $vars['id'] == '' || $vars['id'] == '0' )
        {
            $_SESSION['alert'] = array(
                                        'type'          => 'danger',
                                        'message'       => $this->lang['ERR_LANG_TEXT_NOT_EXISTS'],
                                        'filters'       => $this->list_filters,
                                        'pagination'    => $this->pagination,
            );
            header('Location: /'.$this->folder.'/'.$this->lang['LANG_TEXTS_LINK']);
            exit;
        }

        $error_ajax = array();

        if ( $data['submit'] )
        {
            if ( !sizeof( $error_ajax ) )
            {
                $this->logger->info('===============' . __METHOD__ . ' Lang ' . $vars['id'] . ' | User ' . $this->user . ' ===================================================');

                $lang_text->delete();

                $lang_text_name->deleteByLangText( $lang_text->getId() );

                // Send success to be displayed
                $response['status'] = 'OK';
                $response['msg'] = $this->lang['LANG_TEXT_DELETED'];
                $response['action'] = '/' . $this->folder . '/langtexts';
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
//$txt = 'Response on error '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($response, TRUE));

                // Send errors to be displayed
                echo json_encode($response);
                exit();
            }
        }
        else
        {
            if ( !empty( $lang_text->getId() ) )
            {

            }
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
            $data['names'][$lang_temp['code_2a']]['lang_code_2a'] = $lang_temp['code_2a'];
            $data['names'][$lang_temp['code_2a']]['lang_name'] = $lang_temp['iso_name'];
            $data['names'][$lang_temp['code_2a']]['text'] = $this->utils->request_var_array( 'texts', $lang_temp['code_2a'], '', 'POST', true);
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

        // context select options list
        if ( $lang_text->getContext() == '' )
        {
            $data['context_options'] .= '<option value="" selected="selected"><b>' . $this->lang['SELECT'] . '</b></option>';
            $data['context_options'] .= '<option disabled>' . str_repeat('&#x2500', 16) . '</option>';
        }
        $data['context_options'] .= '<option value="app"' . (( $lang_text->getContext() == 'app') ? ' selected="selected" ' : '') . '>'.'app'.'</option>';
        $data['context_options'] .= '<option value="web"' . (( $lang_text->getContext() == 'web') ? ' selected="selected" ' : '') . '>'.'web'.'</option>';
        $data['context_options'] .= '<option value="errors"' . (( $lang_text->getContext() == 'errors') ? ' selected="selected" ' : '') . '>'.'errors'.'</option>';
        $data['context_options'] .= '<option value="security"' . (( $lang_text->getContext() == 'security') ? ' selected="selected" ' : '') . '>'.'security'.'</option>';
        $data['context_options'] .= '<option value="legal"' . (( $lang_text->getContext() == 'legal') ? ' selected="selected" ' : '') . '>'.'legal'.'</option>';

        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/langtextForm.html.twig', array(
            'reg' => $lang_text->getReg(),
            'filters' => $this->list_filters,
            'pagination' => $this->pagination,
            'data' => $data,
            'cancel' => '/'.$this->folder.'/langtexts',
        ));
    }
}
