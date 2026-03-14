<?php

namespace src\controller\views\app;

use \src\controller\baseViewController;
use \src\controller\entity\N8NleadController;
use \src\controller\entity\N8NleadEmailController;

use src\controller\entity\leadOriginController;
use src\controller\entity\leadMarketController;

use src\controller\entity\langController;
use src\controller\entity\langNameController;

use DateTime;
use DateTimeZone;

class N8NleadEditViewController extends baseViewController
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
                                    'company' => array(
                                        'type' => 'text',
                                        'caption' => '',
                                        'placeholder' => '',
                                        'width' => '0',	// if 0 uses the rest of the row
                                        'value' => '',
                                        'value_previous' => '',
                                    ),
                                    'phone' => array(
                                        'type' => 'text',
                                        'caption' => '',
                                        'placeholder' => '',
                                        'width' => '0',	// if 0 uses the rest of the row
                                        'value' => '',
                                        'value_previous' => '',
                                    ),
                                    'phone_mobile' => array(
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
     * @Route('/app/n8n_lead/edit/id', name='app_n8n_lead_edit')
     *
     * @param POST
     *
     * @return object    Twig template
     */
    public function edititemAction( $vars )
    {
        $this->logger->info('==============='.__METHOD__.' N8N Lead process '.$vars['id'].' | User '.$this->user.' ===================================================');
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/n8nleadEditViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']) );

        $form_action = $this->folder.'/n8n_lead/editor';

        $this->pagination = $this->utils->request_pagination( $this->pagination );
        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        $reg = new N8NleadController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $reg_original = new N8NleadController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $lead_email = new N8NleadEmailController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $reg->setId(( isset( $vars['id'] ) )? $vars['id'] : '0');
        //$reg->setLeadKey( $this->utils->request_var( 'token', '', 'ALL') );
        $reg->setDateReg( $this->utils->request_var( 'date_reg', '', 'ALL') );
        $reg->setName( $this->utils->request_var( 'name', '', 'ALL', true) );
        $reg->setCompany( $this->utils->request_var( 'company', '', 'ALL', true) );
        $reg->setPosition( $this->utils->request_var( 'position', '', 'ALL', true) );
        $reg->setLocale( $this->utils->request_var( 'locale', '', 'ALL') );
        $reg->setLinkedIn( $this->utils->request_var( 'linkedin', '', 'ALL', true) );
        $reg->setInstagram( $this->utils->request_var( 'instagram', '', 'ALL', true) );
        $reg->setTwitter( $this->utils->request_var( 'twitter', '', 'ALL', true) );
        $reg->setEmail( $this->utils->request_var( 'email', '', 'ALL', true) );
        $reg->setDomainName( $this->utils->request_var( 'domain_name', '', 'ALL', true) );
        $reg->setPhone( $this->utils->request_var( 'phone', '', 'ALL', true) );
        $reg->setPhoneMobile( $this->utils->request_var( 'phone_mobile', '', 'ALL', true) );
        $reg->setOrigin( $this->utils->request_var( 'origin', '', 'ALL') );
        $reg->setMarket( $this->utils->request_var( 'market', '', 'ALL') );
        $reg->setConscience( $this->utils->request_var( 'conscience', '', 'ALL') );
        //$reg->setBulkInfo( $this->utils->request_var( 'bulk_info', '', 'ALL', true) );
        $reg->setActive( $this->utils->request_var( 'active', '1', 'ALL') );

        $data = array(
            'auth_token'     => ( isset( $_POST['auth_token']) )? $_POST['auth_token'] : $this->utils->generateFormToken($form_action),
            'submit'         => ( isset($_POST['btn_submit'])) ? true : false,
            'action'         => ( $reg->getId() == '0' )? 'add' : 'edit',
            'emails' => array(),
            'locale_options' => '',
            'origin_options' => '',
            'market_options' => '',
            'conscience_options' => '',
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
                    'section' => $this->lang['N8N_LEAD_EDIT'],
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
                else if ( strlen( $reg->getName() ) > 200 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['name'],
                        'msg' => sprintf( $this->lang['ERR_NAME_LONG'], '200' ),
                    );
                }
                else if ( $reg->leadsWithSameName( $reg->getName(), 'id', $reg->getId() ) )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['name'],
                        'msg' => $this->lang['ERR_NAME_EXISTS'],
                    );
                }
            }

            $match = '/^[A-Za-z0-9-_.+%]+@[A-Za-z0-9-.]+\.[A-Za-z]{2,20}$/';
            if ( empty( $reg->getEmail() ))
            {
                $error_ajax[] = array (
                    'dom_object' => ['email'],
                    'msg' => $this->lang['ERR_MAIL_NEEDED'],
                );
            }
            else
            {
                if ( strlen( $reg->getEmail() ) > 100 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['email'],
                        'msg' => sprintf( $this->lang['ERR_EMAIL_LONG'], '100' ),
                    );
                }
                else if ( strlen( $reg->getEmail() ) < 6 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['email'],
                        'msg' => sprintf( $this->lang['ERR_EMAIL_SHORT'], '7' ),
                    );
                }
                else if (!preg_match($match, $reg->getEmail() ) )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['email'],
                        'msg' => $this->lang['ERR_MAIL_BAD'],
                    );
                }
                else if ( $reg->leadsWithSameEmail( $reg->getEmail(), 'id', $reg->getId() ) )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['email'],
                        'msg' => $this->lang['ERR_MAIL_EXISTS'],
                    );
                }
            }

            if ( !sizeof( $error_ajax ) )
            {
                if ( $data['action'] == 'add' )
                {
//$txt = '========== NEW =========='.PHP_EOL; fwrite($this->myfile, $txt);
                    $reg->persistORL();

                    $reg->setLeadKey( md5( $reg->getId()) );
                    $reg->persist();

                }
                else
                {
//$txt = '========== EDIT =========='.PHP_EOL; fwrite($this->myfile, $txt);
                    $reg_original->getRegbyId( $reg->getId() );

                    $reg->setLeadKey( $reg_original->getLeadKey() );
                    $reg->setBulkInfo( $reg_original->getBulkInfo() );

                    $reg->persistORL();
                }

                // Send success to be displayed
                $response['status'] = 'OK';
                $response['msg'] = $this->lang['N8N_LEAD_SAVED'];
                $response['action'] = '/'.$this->folder.'/n8n_leads';
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
                    $_SESSION['alert'] = array(
                                                'type'=>'danger',
                                                'message' => $this->lang['ERR_N8N_LEAD_NOT_EXISTS']
                    );
                    header('Location: /'.$this->folder.'/'.$this->lang['N8N_LEADS_LINK']);
                    exit;
                }
            }
        }

        // Emails
        if ( $data['action'] != 'add')
        {
            $filter_select = array(
                                    'lead' => $reg->getId(),
            );
            $extra_select = 'ORDER BY `date_sent`';
            $rows = $lead_email->getAll( $filter_select, $extra_select);
            foreach ( $rows as $row ) {
                $data['emails'][] = array(
                                            'subject' => $row['subject'],
                                            'body' => $row['body'],

                );
            }
        }

        // Locale options
        if ( $reg->getLocale() == '')
        {
            $data['locale_options'] .= '<option value="" selected="selected">'.$this->lang['LANG_SELECT'].'</option>';
            $data['locale_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        }

        $lang = new langController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $lang_name = new langNameController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $rows = $lang->getActiveLangs();
        foreach ( $rows as $row )
        {
            $lang_name->getRegbyCodeAndLang( $row['code_2a'], $this->session->getLanguageCode2a());
            $data['locale_options'] .= '<option value="'.$row['code_2a'].'"'.(( $reg->getLocale() == $row['code_2a'] )? ' selected="selected" ' : '').'>'.$lang_name->getName().'</option>';
        }

        // Lead market select options list
        if ( $reg->getMarket() == '' )
        {
            $data['market_options'] .= '<option value="" selected="selected">'.$this->lang['LEAD_MARKET_SELECT'].'</option>';
            $data['market_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        }
        $filter_select = '';
        $extra_select = 'ORDER BY `name`';
        $lead_market = new leadMarketController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $rows = $lead_market->getAll( $filter_select, $extra_select);
        foreach ( $rows as $row ) {
            $data['market_options'] .= '<option value="' . $row['id'] . '"' . (( $reg->getMarket() == $row['id']) ? ' selected="selected" ' : '') . '>' . $row["name"] . '</option>';
        }

        // Lead origin select options list
        if ( $reg->getOrigin() == '' )
        {
            $data['origin_options'] .= '<option value="" selected="selected">'.$this->lang['LEAD_ORIGIN_SELECT'].'</option>';
            $data['origin_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        }
        $filter_select = '';
        $extra_select = 'ORDER BY `name`';
        $lead_origin = new leadOriginController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $rows = $lead_origin->getAll( $filter_select, $extra_select);
        foreach ( $rows as $row ) {
            $data['origin_options'] .= '<option value="' . $row['id'] . '"' . (( $reg->getMarket() == $row['id']) ? ' selected="selected" ' : '') . '>' . $row["name"] . '</option>';
        }

        // Conscience select options list
        if ( empty( $reg->getConscience() ) )
        {
            $data['conscience_options'] .= '<option value="">'.$this->lang['SELECT'].'</option>';
            $data['conscience_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        }
        $data['conscience_options'] .= '<option value="unconscious"'.(( $reg->getMarket() == 'unconscious') ? ' selected="selected" ' : '').'>'.$this->lang['LEAD_CONSCIENCE_UNCONSCIOUS'].'</option>';
        $data['conscience_options'] .= '<option value="know_problem"'.(( $reg->getMarket() == 'know_problem') ? ' selected="selected" ' : '').'>'.$this->lang['LEAD_CONSCIENCE_KNOW_PROBLEM'].'</option>';
        $data['conscience_options'] .= '<option value="know_solutions"'.(( $reg->getMarket() == 'know_solutions') ? ' selected="selected" ' : '').'>'.$this->lang['LEAD_CONSCIENCE_KNOW_SOLUTIONS'].'</option>';
        $data['conscience_options'] .= '<option value="know_product"'.(( $reg->getMarket() == 'know_product') ? ' selected="selected" ' : '').'>'.$this->lang['LEAD_CONSCIENCE_KNOW_PRODUCT'].'</option>';
        $data['conscience_options'] .= '<option value="aware"'.(( $reg->getMarket() == 'aware') ? ' selected="selected" ' : '').'>'.$this->lang['LEAD_CONSCIENCE_AWARE'].'</option>';

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose($this->myfile);
        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/N8N_leadForm.html.twig', array(
            'reg' => $reg->getReg(),
            'filters' => $this->list_filters,
            'pagination' => $this->pagination,
            'data' => $data,
            'cancel' => '/'.$this->folder.'/n8n_leads',
        ));
    }
}