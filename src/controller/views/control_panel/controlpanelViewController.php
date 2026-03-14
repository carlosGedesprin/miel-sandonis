<?php

namespace src\controller\views\control_panel;

use \src\controller\baseViewController;

use \src\controller\entity\accountController;
use \src\controller\entity\userController;
use \src\controller\entity\userProfileController;

use DateTime;
use DateTimeZone;

class controlpanelViewController extends baseViewController
{
    private $crypt_options = array(
        'cost' => 12,
    );

    private $user_connected;
    private $account_connected;
    private $folder = 'control_panel';

    /**
    * @Route("control_panel/dashboard", name="control_panel_dashboard")
    */
    public function dashboardAction()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/cp_controlpanelViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//$txt = 'Post'.PHP_EOL; fwrite( $this->myfile, $txt );
//fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $loadinfo = new loadInfoController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $dashboard_data = $loadinfo->getLoadInfo();

        $this->getUserIdentity();

//$txt = 'Dashboard Data'.PHP_EOL; fwrite( $this->myfile, $txt );
//fwrite($this->myfile, print_r($dashboard_data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose($this->myfile);
        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/dashboard.html.twig', array(
            'dashboard_data' => $dashboard_data,
        ));
    }

    /**
     * @Route("control_panel/my_script", name="control_panel_my_script")
     */
    public function myScriptAction()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/cp_controlpanelViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//$txt = 'Post:'.PHP_EOL; fwrite( $this->myfile, $txt );
//fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $loadinfo = new loadInfoController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $dashboard_data = $loadinfo->getLoadInfo();

        $this->getUserIdentity();

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose($this->myfile);
        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/my_script.html.twig', array(
            'dashboard_data' => $dashboard_data,
        ));
    }

    /**
     * @Route("control_panel/change_tax_data", name="control_panel_change_tax_data")
     */
    public function accountChangeTaxdataAction()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/cp_controlpanelViewController'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);

        $form_action = $this->folder.'/change_tax_data/editor';

        $loadinfo = new loadInfoController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $dashboard_data = $loadinfo->getLoadInfo();

        $this->getUserIdentity();

        $reg = new accountController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $reg->getRegbyId( $this->account_connected->getId() );

        $reg->setCompany( $this->utils->request_var( 'company', $reg->getCompany(), 'ALL',true) );
        $reg->setAddress( $this->utils->request_var( 'address', $reg->getAddress(), 'ALL',true) );
        $reg->setPostCode( $this->utils->request_var( 'post_code', $reg->getPostCode(), 'ALL',true) );
        $reg->setCountry( $this->utils->request_var( 'country', $reg->getCountry(), 'ALL') );
        $reg->setRegion( $this->utils->request_var( 'region', $reg->getRegion(), 'ALL') );
        $reg->setCity( $this->utils->request_var( 'city', $reg->getCity(), 'ALL') );
        $reg->setAltCity( $this->utils->request_var( 'alt_city', $reg->getAltCity(), 'ALL',true) );
        $reg->setVat( $this->utils->request_var( 'vat', $reg->getVat(), 'ALL', true ) );

        $data = array(
            'auth_token' => ( isset( $_POST['auth_token']) )? $_POST['auth_token'] : $this->utils->generateFormToken($form_action),
            'submit' => (isset($_POST['btn_submit'])) ? true : false,
            'url_action' => '/'.$this->folder.'/change_tax_data',
// ********************** Locations start *******************************************
            'country_options' => '',
            'region_options' => '',
            'city_options' => '',
// ********************** Locations end *******************************************
        );
//$txt = 'Post =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Data =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);


        $error_ajax = array();
              
        if ( $data['submit'] )
        {
//$txt = 'Submit =========='.PHP_EOL; fwrite($this->myfile, $txt);
            if ( !empty( $reg->getVat() ) )
            {
                if ( strlen( $reg->getVat() ) < 2 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['vat'],
                        'msg' => sprintf( $this->lang['ERR_CUSTOMER_VAT_SHORT'], '2' ),
                    );
                }    
                else if ( strlen( $reg->getVat() ) > 25 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['vat'],
                        'msg' => sprintf($this->lang['ERR_CUSTOMER_VAT_LONG'], '25'),
                    );
                }
            }
        
            if ( !sizeof( $error_ajax ) )
            {
                // Fields with special treatment
                // ********************** Locations start *******************************************
                if ( $reg->getCity() == '-') $reg->setCity('0'); // 0 means there is an alt city
                // ********************** Locations end *******************************************

                $reg->persistORL();

                // Send success to be displayed
                $response['status'] = 'OK';
                $response['msg'] = $this->lang['ACCOUNT_FISCAL_DETAILS_SAVED'];
                $response['action'] = '/'.$this->folder.'/dashboard';
                
//$txt = 'Response'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($response, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                echo json_encode($response);
                exit();
            }
            else
            {
                // Errors found
                // Renew CSRF - It gives issues with ajax and session destroy in startup
                //$data['auth_token'] = $this->utils->generateFormToken($form_action);

//$txt = '---> Errors'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($error_ajax, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                $response['status'] = 'KO';
                foreach( $error_ajax as $key => $value )
                {
                    $response['errors'][] = $value;
                }
//$txt = 'Response charge on error '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($response, TRUE));

                // Send errors to be displayed
                echo json_encode($response);
                exit();
            }
        }
        else
        {
            // Field with special treatment
            // ********************** Locations start *******************************************
            if ( $reg->getCity() == '0' ) $reg->setCity('-');
            // ********************** Locations end *******************************************
        }

// ********************** Locations start *******************************************
        // Country select options list
        require_once(APP_ROOT_PATH.'/src/util/view_selects/country_all.php');

        // Region select options list
        require_once(APP_ROOT_PATH.'/src/util/view_selects/region_country.php');

        // City select options list
        require_once(APP_ROOT_PATH.'/src/util/view_selects/city_region_country.php');
// ********************** Locations end *******************************************

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose($this->myfile);
        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/account_tax_data.html.twig', array(
            'reg' => $reg->getReg(),
            'data' => $data,
            'cancel' => '/'.$this->folder.'/dashboard',
            'dashboard_data' => $dashboard_data,
        ));
    }

    /**
     * Get user identity
     */
    public function getUserIdentity()
    {
        $this->user_connected = new userController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $this->user_connected->getRegbyId( $this->user );

        $this->account_connected = new accountController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $this->account_connected->getRegbyId( $this->user_connected->getAccount() );
    }
}