<?php

namespace src\controller\views\app;

use \src\controller\baseViewController;

use \src\controller\entity\siteMapController;

use DateTime;
use DateTimeZone;

class sitemapEditViewController extends baseViewController
{
    private $table = 'site_map';

    private $list_filters = array(
                                    'page_title' => array(
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
     * @Route('/app/sitemap/edit/id', name='app_sitemap_edit')
     *
     * @param POST
     *
     * @return object    Twig template
     */
    public function edititemAction( $vars )
    {
        $this->logger->info('==============='.__METHOD__.' Group '.$vars['id'].' | User '.$this->user.' ===================================================');
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/sitemapEditViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $reg = new siteMapController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']) );

        $form_action = $this->folder.'/sitemap/editor';

        $this->pagination = $this->utils->request_pagination( $this->pagination );
        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        $reg->setId( (isset( $vars['id'] ) )? $vars['id'] : '0' );
        $reg->setPageTitle( $this->utils->request_var( 'page_title', '', 'ALL', true)  );
        $reg->setDescription( $this->utils->request_var( 'description', '', 'ALL', true)  );
        $reg->setSubdomain( $this->utils->request_var( 'subdomain', '', 'ALL', true) );
        $reg->setSlug( $this->utils->request_var( 'slug', '', 'ALL', true) );
        $reg->setChangefreg( $this->utils->request_var( 'changefreg', '', 'ALL', true) );
        $reg->setCreatedDate( $this->utils->request_var( 'createddate', $now->format('d-m-Y'), 'ALL', true) );
        $reg->setPriority( $this->utils->request_var( 'priority', '', 'ALL', true) );
        $reg->setActive( $this->utils->request_var( 'active', '1', 'ALL', true) );

        $data = array(
            'auth_token'    => ( isset( $_POST['auth_token']) )? $_POST['auth_token'] : $this->utils->generateFormToken($form_action),
            'submit'        => (isset($_POST['btn_submit'])) ? true : false,
            'action'        => ( $reg->getId() == '0' ) ? 'add' : 'edit',
            'changefreg_options' => '',
            'subdomain_options' => '',
        );

        $error_ajax = array();

//$txt = 'On start function start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'POST =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'REG =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
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
                    'alert_type' => 'danger',
                    'title' => $this->lang['WARNING'],
                    'message' => $this->lang['ERR_BAD_TOKEN'],
                    'redirect' => '/logout',
                ));
            }
            */
            if ( empty($reg->getPageTitle()) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['page_title'],
                    'msg' => $this->lang['ERR_SITEMAP_PAGE_TITLE_NEEDED'],
                );
            }
            else
            {
                if ( strlen($reg->getPageTitle()) < 2 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['page_title'],
                        'msg' => sprintf( $this->lang['ERR_SITEMAP_PAGE_TITLE_SHORT'], '2' )
                    );
                }
                else if ( strlen($reg->getPageTitle()) > 255 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['page_title'],
                        'msg' => sprintf($this->lang['ERR_SITEMAP_PAGE_TITLE_LONG'],'255')
                    );
                }
                elseif ( $this->db->fetchOne( $reg->getTableName(), 'id', ['page_title' => $reg->getPageTitle()], ' AND id <> '.$reg->getId()) )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['page_title'],
                        'msg' => $this->lang['ERR_SITEMAP_PAGE_TITLE_EXISTS'],
                    );
                }
            }

            if ( empty($reg->getSlug()) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['slug'],
                    'msg' => $this->lang['ERR_SITEMAP_SLUG_NEEDED'],
                );
            }
            else
            {
                if ( strlen($reg->getSlug()) < 1 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['slug'],
                        'msg' => sprintf( $this->lang['ERR_SITEMAP_SLUG_SHORT'], '1' ),
                    );
                }
                else if ( strlen($reg->getSlug()) > 255 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['slug'],
                        'msg' => sprintf( $this->lang['ERR_SITEMAP_SLUG_LONG'], '255' ),
                    );
                }
                else if ( substr($reg->getSlug(), 0, 1) != '/' )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['slug'],
                        'msg' => $this->lang['ERR_SITEMAP_SLUG_SLASH'],
                    );
                }
                elseif ( $this->db->fetchOne( $this->table, 'id', ['slug' => $reg->getSlug()], ' AND id <> '.$reg->getId()) )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['slug'],
                        'msg' => $this->lang['ERR_SITEMAP_SLUG_EXISTS'],
                    );
                }
            }

            if ( empty($reg->getChangefreg()) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['changefreg'],
                    'msg' => $this->lang['ERR_SITEMAP_CHANGEFREG_NEEDED'],
                );
            }

            if ( empty($reg->getPriority()) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['priority'],
                    'msg' => $this->lang['ERR_SITEMAP_PRIORITY_NEEDED'],
                );
            }
            else
            {
                if ( intval( $reg->getPriority() ) > 1 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['priority'],
                        'msg' => $this->lang['ERR_SITEMAP_PRIORITY_HIGH'],
                    );
                }
            }

            if ( empty($reg->getCreatedDate()) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['createddate'],
                    'msg' => $this->lang['ERR_SITEMAP_CREATEDDATE_NEEDED'],
                );
            }
            
            if ( !sizeof( $error_ajax ) )
            {
                // Fields with special treatment
                $reg->setSubdomain( ( $reg->getSubdomain() == '-' )? '' : $reg->getSubdomain() ) ;
                $reg->setCreatedDate(( $reg->getCreatedDate() == '' )? NULL : DateTime::createFromFormat('d-m-Y', $reg->getCreatedDate(), new DateTimeZone($this->session->config['time_zone'])) );

                if ( $data['action'] == 'add' )
                {
                    // new record
                    $reg->persistORL();
                }
                else
                {
                    // Edit record
                    $reg->persistORL();
                }

                // Send success to be displayed
                $response['status'] = 'OK';
                $response['msg'] = $this->lang['SITEMAP_SAVED'];
                $response['action'] = '/'.$this->folder.'/'.$this->lang['SITEMAPS_LINK'];
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
            // not submit
            
            if ( $data['action'] == 'add' )
            {
                // new record
            }
            else
            {
                // Edit record
                if ( $reg->getRegbyId( $reg->getId() ) )
                {
                    // Fields with special treatment
                    $reg->setCreatedDate( ( $reg->getCreatedDate() == '' )? NULL : $reg->getCreatedDate()->format('d-m-Y') );

//$txt = 'REG =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                }
                else
                {
                    $_SESSION['alert'] = array(
                        'type'=>'danger',
                        'message'=>$this->lang['ERR_SITEMAP_NOT_EXISTS']
                    );
                    header('Location: /'.$this->folder.'/'.$this->lang['SITEMAPS_LINK']);
                    exit;
                }
            }
        }

        if ( $reg->getSubdomain() == '' ) $reg->setSubdomain('-');

        // subdomain options
        if ( $reg->getSubdomain() == '' )
        {
            $data['subdomain_options'] .= '<option value="" selected="selected"><b>' . $this->lang['SELECT'] . '</b></option>';
            $data['subdomain_options'] .= '<option disabled>' . str_repeat('&#x2500', 16) . '</option>';
        }
        $data['subdomain_options'] .= '<option value="-"' . (($reg->getSubdomain() == '-') ? ' selected="selected" ' : '') . '>'.$this->lang['SITEMAP_SUBDOMAIN_ROOT'].'</option>';
        $data['subdomain_options'] .= '<option value="blog"' . (($reg->getSubdomain() == 'blog') ? ' selected="selected" ' : '') . '>'.$this->lang['SITEMAP_SUBDOMAIN_BLOG'].'</option>';

        // changefreg options
        if ( $reg->getChangefreg() == '' )
        {
            $data['changefreg_options'] .= '<option value="" selected="selected"><b>' . $this->lang['SELECT'] . '</b></option>';
            $data['changefreg_options'] .= '<option disabled>' . str_repeat('&#x2500', 16) . '</option>';
        }
        $data['changefreg_options'] .= '<option value="always"' . (($reg->getChangefreg() == 'always') ? ' selected="selected" ' : '') . '>'.$this->lang['SITEMAP_CHANGEFREG_ALWAYS'].'</option>';
        $data['changefreg_options'] .= '<option value="hourly"' . (($reg->getChangefreg() == 'hourly') ? ' selected="selected" ' : '') . '>'.$this->lang['SITEMAP_CHANGEFREG_HOURLY'].'</option>';
        $data['changefreg_options'] .= '<option value="daily"' . (($reg->getChangefreg() == 'daily') ? ' selected="selected" ' : '') . '>'.$this->lang['SITEMAP_CHANGEFREG_DAILY'].'</option>';
        $data['changefreg_options'] .= '<option value="weekly"' . (($reg->getChangefreg() == 'weekly') ? ' selected="selected" ' : '') . '>'.$this->lang['SITEMAP_CHANGEFREG_WEEKLY'].'</option>';
        $data['changefreg_options'] .= '<option value="monthly"' . (($reg->getChangefreg() == 'monthly') ? ' selected="selected" ' : '') . '>'.$this->lang['SITEMAP_CHANGEFREG_MONTHLY'].'</option>';
        $data['changefreg_options'] .= '<option value="yearly"' . (($reg->getChangefreg() == 'yearly') ? ' selected="selected" ' : '') . '>'.$this->lang['SITEMAP_CHANGEFREG_YEARLY'].'</option>';
        $data['changefreg_options'] .= '<option value="never"' . (($reg->getChangefreg() == 'never') ? ' selected="selected" ' : '') . '>'.$this->lang['SITEMAP_CHANGEFREG_NEVER'].'</option>';

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose($this->myfile);
        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/sitemapForm.html.twig', array(
            'reg' => $reg->getReg(),
            'filters' => $this->list_filters,
            'pagination' => $this->pagination,
            'data' => $data,
            'cancel' => '/'.$this->folder.'/'.$this->lang['SITEMAPS_LINK'],
        ));
    }
}