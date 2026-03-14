<?php

namespace  src\controller\views\app;

use \src\controller\baseViewController;
use \src\controller\entity\N8NleadController;
use \src\controller\entity\N8NleadEmailController;

use \src\controller\entity\langController;
use \src\controller\entity\langNameController;
use \src\controller\entity\leadMarketController;
use \src\controller\entity\leadOriginController;

class N8NleadDeleteViewController extends baseViewController
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
     * @Route('/app/n8n_lead/delete/id', name='app-n8n_lead_delete')
     *
     * @param POST
     *
     * @return object    Twig template
     */
    public function deleteitemAction( $vars )
    {
//$myfile = fopen(APP_ROOT_PATH.'/var/logs/n8nleadDeleteViewController'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($myfile, $txt);
//$txt = 'POST =========='.PHP_EOL; fwrite($myfile, $txt);
//fwrite($myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($myfile, $txt);

        $form_action = $this->folder.'/n8n_lead/delete';

        $this->pagination = $this->utils->request_pagination( $this->pagination );
        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        if ( !isset( $vars['id']) || $vars['id'] == '' || $vars['id'] == '0' )
        {
            $_SESSION['alert'] = array(
                'type'          => 'danger',
                'message'       => $this->lang['ERR_N8N_LEAD_NOT_EXISTS'],
                'filters'       => $this->list_filters,
                'pagination'    => $this->pagination,
            );
            header('Location: /'.$this->folder.'/'.$this->lang['N8N_LEADS_LINK']);
            exit;
        }

        $reg = new N8NleadController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $reg_email = new N8NleadEmailController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $reg->getRegbyId( $vars['id'] );

        $data = array(
            'auth_token' => ( isset( $_POST['auth_token']) )? $_POST['auth_token'] : $this->utils->generateFormToken($form_action),
            'submit' => (isset($_POST['btn_submit'])) ? true : false,
            'action' => 'delete',
            'emails' => array(),
            'locale_options' => '',
            'origin_options' => '',
            'market_options' => '',
            'conscience_options' => '',
        );

        $error_ajax = array();

        if ( $data['submit'] )
        {
            // CSRF Token validation
            /*
            $valid = $this->utils->verifyFormToken($form_action, $data['auth_token'], 1000);
            if(!$valid){
                return $this->twig->render('app/default/common/show_message.html.twig', array(
                    'section' => $this->lang['N8N_LEAD_EDIT'],
                    'alert_type' => 'danger',
                    'title' => $this->lang['WARNING'],
                    'message' => $this->lang['ERR_BAD_TOKEN'],
                    'redirect' => '/logout',
                ));
            }
            */

            if ( !sizeof( $error_ajax ) )
            {
                $this->logger->info('==============='.__METHOD__.' N8N Lead to delete '.$vars['id'].' | User '.$this->user.' ===================================================');

                $filter_select = array(
                                        'lead' => $reg->getId(),
                );
                $extra_select = '';
                $rows = $reg_email->getAll( $filter_select, $extra_select);
                foreach ( $rows as $row )
                {
                    $reg_email->getRegbyId( $row['id'] );
                    $reg_email->delete();
                }

                $reg->delete();

                $this->pagination['num_page'] = '1';

                // Send success to be displayed
                $response['status'] = 'OK';
                $response['msg'] = $this->lang['N8N_LEAD_DELETED'];
                $response['action'] = '/'.$this->folder.'/'.$this->lang['N8N_LEADS_LINK'];
                echo json_encode($response);
                exit();
            }
            else
            {
                // Renew CSRF - It gives issues with ajax and session destroy in startup
                //$data['auth_token'] = $this->utils->generateFormToken($form_action);

                $response['status'] = 'KO';
                foreach( $error_ajax as $key => $value )
                {
                    $response['errors'][] = $value;
                }
//$txt = 'Response on error '.PHP_EOL; fwrite($myfile, $txt);
//fwrite($myfile, print_r($response, TRUE));

                // Send errors to be displayed
                echo json_encode($response);
                exit();
            }
        }
        else
        {
            if ( !empty( $reg->getId() ) )
            {

            }
            else
            {
                $_SESSION['alert'] = array(
                    'type'          => 'danger',
                    'message'       => $this->lang['ERR_N8N_LEAD_NOT_EXISTS'],
                    'filters'       => $this->list_filters,
                    'pagination'    => $this->pagination,
                );
                header('Location: /'.$this->folder.'/'.$this->lang['N8N_LEADS_LINK']);
                exit();
            }
        }

        // Emails
        if ( $data['action'] != 'add')
        {
            $filter_select = array(
                'lead' => $reg->getId(),
            );
            $extra_select = 'ORDER BY `date_sent`';
            $rows = $reg_email->getAll( $filter_select, $extra_select);
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

        return $this->twig->render('app/default/'.$this->folder.'/N8N_leadForm.html.twig', array(
            'reg' => $reg->getReg(),
            'filters' => $this->list_filters,
            'pagination' => $this->pagination,
            'data' => $data,
            'cancel' => '/'.$this->folder.'/'.$this->lang['N8N_LEADS_LINK'],
        ));
    }
}
