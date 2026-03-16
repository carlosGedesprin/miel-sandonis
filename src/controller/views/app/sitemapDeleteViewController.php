<?php

namespace  src\controller\views\app;

use \src\controller\baseViewController;

use \src\controller\entity\siteMapController;

class sitemapDeleteViewController extends baseViewController
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
     * @Route("/app/sitemap/delete/id", name="app_sitemap_delete")
     *
     * @param POST
     *
     * @return object   Twig template
     */
    public function deleteitemAction( $vars )
    {
        $form_action = $this->folder.'/sitemap/delete';

        $this->pagination = $this->utils->request_pagination( $this->pagination );
        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        if ( !isset( $vars['id']) || $vars['id'] == '' || $vars['id'] == '0' )
        {
            $_SESSION['alert'] = array(
                'type'          => 'danger',
                'message'       => $this->lang['ERR_SITEMAP_NOT_EXISTS'],
                'filters'       => $this->list_filters,
                'pagination'    => $this->pagination,
            );
            header('Location: /'.$this->folder.'/'.$this->lang['SITEMAPS_LINK']);
            exit;
        }

        $reg = new siteMapController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $reg->getRegbyId( $vars['id'] );
        
        $data = array(
            'auth_token' => ( isset( $_POST['auth_token']) )? $_POST['auth_token'] : $this->utils->generateFormToken($form_action),
            'submit' => (isset($_POST['btn_submit'])) ? true : false,
            'action' => 'delete',
            'changefreg_options' => '',
            'subdomain_options' => '',
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

            // Plan is in a website
            /*
            if ( $this->db->fetchOne( 'website', 'id', ['product' => $reg->getPlanKey() ] ))
            {
                $error_ajax[] = array (
                    'dom_object' => [''],
                    'msg' => $this->lang['ERR_PLAN_IN_WEBSITE'],
                );
            }
            */

            if ( !sizeof( $error_ajax ) )
            {
                $this->logger->info('==============='.__METHOD__.' Site map '.$vars['id'].' | User '.$this->user.' ===================================================');

                $reg->delete();
                
                $this->pagination['num_page'] = '1';

                // Send success to be displayed
                $response['status'] = 'OK';
                $response['msg'] = $this->lang['SITEMAP_DELETED'];
                $response['action'] = '/'.$this->folder.'/'.$this->lang['SITEMAPS_LINK'];
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
                $reg->setSubdomain( ( $reg->getSubdomain() == '-' )? '' : $reg->getSubdomain() ) ;
                $reg->setCreatedDate( ( $reg->getCreatedDate() == '' )? NULL : $reg->getCreatedDate()->format('d-m-Y') );
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

        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/sitemapForm.html.twig', array(
            'reg' => $reg->getReg(),
            'filters' => $this->list_filters,
            'pagination' => $this->pagination,
            'data' => $data,
            'cancel' => '/'.$this->folder.'/'.$this->lang['SITEMAPS_LINK'],
        ));
    }
}
