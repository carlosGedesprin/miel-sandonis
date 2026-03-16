<?php

namespace src\controller\views\app;

use \src\controller\baseViewController;

use \src\controller\entity\userController;
use \src\controller\entity\userProfileController;
use \src\controller\entity\userNotesController;

use DateTime;
use DateTimeZone;

class userDeleteViewController extends baseViewController
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
                                'account' => array(
                                                    'type' => 'select',
                                                    'caption' => '',
                                                    'placeholder' => '',
                                                    'width' => '0',	// if 0 uses the rest of the row
                                                    'value' => '',
                                                    'value_previous' => '',
                                                    'chain_childs' => '',
                                                    'options' => '',
                                ),
                                'active' => array(
                                                    'type' => 'select',
                                                    'caption' => '',
                                                    'placeholder' => '',
                                                    'width' => '0',	// if 0 uses the rest of the row
                                                    'value' => '',
                                                    'value_previous' => '',
                                                    'chain_childs' => '',
                                                    'options' => '',
                                ),
                                'show_to_staff' => array(
                                                    'type' => 'hidden',
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
     * @Route('/app/user/delete/id', name='app_user_delete')
     *
     * @param POST
     *
     * @return object    Twig template
     */
    public function deleteitemAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/userDeleteViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $form_action = $this->folder.'/user/delete';
        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']) );

        $this->pagination = $this->utils->request_pagination( $this->pagination );
        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        if ( !isset( $vars['id']) || $vars['id'] == '' || $vars['id'] == '0' )
        {
            $_SESSION['alert'] = array(
                'type'          => 'danger',
                'message'       => $this->lang['ERR_USER_NOT_EXISTS'],
                'filters'       => $this->list_filters,
                'pagination'    => $this->pagination,
            );
            header('Location: /'.$this->folder.'/'.$this->lang['USERS_LINK']);
            exit;
        }

        $reg = new userController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $reg->getRegbyId( $vars['id'] );

        $userProfile = new userProfileController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        
        $userProfile->getRegByUser( $reg->getId() );

        $userNotes = new userNotesController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        
        $data = array(
            'auth_token' => ( isset( $_POST['auth_token']) )? $_POST['auth_token'] : $this->utils->generateFormToken($form_action),
            'submit' => (isset($_POST['btn_submit'])) ? true : false,
            'action' => 'delete',
// ********************** Locations start *******************************************
            'country_options' => '',
            'region_options' => '',
            'city_options' => '',
// ********************** Locations end *******************************************
            'locale_options' => '',
        );

        $error_ajax = array();

        //********* File treatment start ******************
        $temp_path = APP_ROOT_PATH.$this->session->config['temp_images_folder'];
        $temp_images_url = $this->startup->getUrlApp().$this->session->config['temp_images_folder'];
        $files_folder = DOCUMENT_ROOT_PATH.'/users/';

        $files = array(
            '1' => array (
                'input_id' => 'user_profile_photo',
                'input_name' => 'user_photo',
                'input_required' => false,
                'file_name' => '',
                'file_extension' => '',
                'file_allowed_extensions' => array('gif', 'jpeg', 'jpg', 'png', 'pdf'),
                'file_link' => '',
                'image_size_height' => '100',
                'image_size_width' => '100',
                'image_error_text' => $this->lang['ERR_USER_PROFILE_IMAGE_NEEDED'],
                'file_entity' => 'userProfile',
                'file_entity_method' => 'Photo',
            ),
        );

        foreach( $files as $i => $file_data )
        {
//$txt = '================= Getting file names from view start =============================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '.................................. '.$i.' ...........................................................'.PHP_EOL; fwrite($this->myfile, $txt);
            $files[$i]['file_name'] = $this->utils->request_var( $files[$i]['input_name'].'_name', '', 'ALL');
            $files[$i]['file_extension'] = $this->utils->request_var( $files[$i]['input_name'].'_extension', '', 'ALL');
//$txt = 'File name '.$files[$i]['file_name'].'.'.$files[$i]['file_extension'].PHP_EOL; fwrite($this->myfile, $txt);
        }
//$txt = '.....................................................................................................'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '================= Getting file names from view end =============================================='.PHP_EOL; fwrite($this->myfile, $txt);
        //********* File treatment end ******************

        if ( $data['submit'] )
        {
            // CSRF Token validation
            /*
            $valid = $this->utils->verifyFormToken($form_action, $data['auth_token'], 1000);
            if(!$valid){
                return $this->twig->render('app/'.$this->session->config['app_skin'].'/common/show_message.html.twig', array(
                    'section' => $this->lang['USER_EDIT'],
                    'alert_type' => 'danger',
                    'title' => $this->lang['WARNING'],
                    'message' => $this->lang['ERR_BAD_TOKEN'],
                    'redirect' => '/logout',
                ));
            }
            */

            // user is main on account
            if ( $item = $this->db->fetchOne( 'account', 'id', ['main_user' => $reg->getId()]) )
            {
                $error_ajax[] = array (
                    'dom_object' => [],
                    'msg' => $this->lang['ERR_USER_ACCOUNT_ISMAIN'],
                );
            }

            if ( !sizeof( $error_ajax ) )
            {
                $this->logger->info('==============='.__METHOD__.' User to delete '.$vars['id'].' | User '.$this->user.' ===================================================');

                // Call api endpoint
                $this->utils->edit_user_api( $reg->getReg(), 'delete');

                $reg->delete();
                // Delete the profile and notes ---> onDelete= Cascade does it for you

                $this->pagination['num_page'] = '1';

                // Send success to be displayed
                $response['status'] = 'OK';
                $response['msg'] = $this->lang['USER_DELETED'];
                $response['action'] = '/'.$this->folder.'/'.$this->lang['USERS_LINK'];
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
//$txt = 'Response charge on error '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($response, TRUE));

                // Send errors to be displayed
                echo json_encode($response);
                exit();
            }
        }
        else
        {
            if ( !empty( $reg->getId() ) )
            {
                $userProfile->getRegByUser( $reg->getId() );

                if ( $this->group == GROUP_SUPER_ADMIN || $this->group == GROUP_ADMIN )
                {
                    $user_notes_total = $userNotes->getAllRegsByUser( $reg->getId() );
                }
                else
                {
                    $userNotes->getRegbyUserAndGroup( $reg->getId(), $this->group );
                    $user_notes_total[] = $userNotes->getReg();
                }

                if ( sizeof($user_notes_total) <= 0 ) {
                    $userNotes->setId('0');
                    $userNotes->setUser( $reg->getId() );
                    $userNotes->setGroup( $this->group );
                    $userNotes->setNotes( '' );
                    $user_notes_total[] = $userNotes->getReg();
                }

//********* File treatment start ******************
//$txt = '================= Getting file names from database and populating files array start =============================================='.PHP_EOL; fwrite($this->myfile, $txt);
                foreach( $files as $i => $file_data )
                {
//$txt = 'Entity method for file '.$i.': '.'get'.$files[$i]['file_entity_method'].PHP_EOL; fwrite($this->myfile, $txt);
                    $method = 'get'.$files[$i]['file_entity_method'];
                    if ( ${$files[$i]['file_entity']}->$method() != '' )
                    {
                        $file_temp = explode('.', ${$files[$i]['file_entity']}->$method());
                        $files[$i]['file_name'] = $file_temp[0];
                        $files[$i]['file_extension'] = $file_temp[1];
                        unset( $file_temp );
//$txt = 'Image name $files array '.$files[$i]['file_name'].'.'.$files[$i]['file_extension'].PHP_EOL; fwrite($this->myfile, $txt);
                    }
                }
//$txt = '================= Getting file names from database and populating files array end =============================================='.PHP_EOL; fwrite($this->myfile, $txt);
                //********* File treatment end ******************

                //********* File treatment start ******************
//$txt = '================= Move from destiny folder to temp folder start =============================================='.PHP_EOL; fwrite($this->myfile, $txt);
                foreach( $files as $i => $value)
                {
//$txt = 'field_files '.$key.':'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($files[$key], TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                    if ( $files[$i]['file_name'] != '' )
                    {
//$txt = 'Copy image to temp ('.$i.')'.PHP_EOL; fwrite($this->myfile, $txt);
                        $destinyFilePath = $files_folder . $files[$i]['file_name'] . '.' . $files[$i]['file_extension'];
                        $tempFilePath = $temp_path . $files[$i]['file_name'] . '.' . $files[$i]['file_extension'];
//$txt = 'File paths '.$destinyFilePath.' -> '.$tempFilePath.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Source image ('.$i.') '.$destinyFilePath.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Destination image ('.$tempFilePath.')'.PHP_EOL; fwrite($this->myfile, $txt);
                        if ( file_exists( $destinyFilePath ) )
                        {
                            if ( file_exists( $tempFilePath ) )
                            {
                                unlink( $tempFilePath );
                            }
                            if ( copy($destinyFilePath, $tempFilePath) )
                            {
//$txt = 'Copied'.PHP_EOL; fwrite($this->myfile, $txt);
                            }
                            else
                            {
//$txt = 'NOT copied'.PHP_EOL; fwrite($this->myfile, $txt);
                            }
//                            clearstatcache(true, $destinyFilePath);
                            $files[$i]['file_link'] = $temp_images_url.$files[$i]['file_name'].'.'.$files[$i]['file_extension'].'?t='.time();
                        }
                        else
                        {
//$txt = 'Source image not exists'.PHP_EOL; fwrite($this->myfile, $txt);
                            $files[$i]['file_name'] = $files[$i]['file_extension'] = '';
                        }
                    } // $files[$key]['file_name'] != ''
                    else
                    {
//$txt = 'No source image on array'.PHP_EOL; fwrite($this->myfile, $txt);
                    }
//$txt = 'Image treated - '.$temp_images_url.$files[$key]['file_name'].'.'.$files[$key]['file_extension'].PHP_EOL; fwrite($this->myfile, $txt);
                } // foreach( $files as $i => $value)
//$txt = '================= Move from user folder to temp folder end =============================================='.PHP_EOL; fwrite($this->myfile, $txt);
                //********* File treatment end ******************

                // Field with special treatment
// ********************** Locations start *******************************************
                if ( $userProfile->getCity() == '0' )  $userProfile->setCity('-');
// ********************** Locations end *******************************************
            }
            else
            {
                $_SESSION['alert'] = array(
                                            'type'          => 'danger',
                                            'message'       => $this->lang['ERR_USER_NOT_EXISTS'],
                                            'filters'       => $this->list_filters,
                                            'pagination'    => $this->pagination,
                );
                header('Location: /'.$this->folder.'/'.$this->lang['USERS_LINK']);
                exit;
            }
        }

        if ( $reg->getLastlogin() != '' ) $reg->setLastlogin( $reg->getLastlogin()->format('d-m-Y H:i:s') );

// ********************** Locations start *******************************************
        // Country select options list
        require_once(APP_ROOT_PATH.'/src/util/view_selects/country_profile_all.php');

        // Region select options list
        require_once(APP_ROOT_PATH.'/src/util/view_selects/region_country_profile.php');

        // City select options list
        require_once(APP_ROOT_PATH.'/src/util/view_selects/city_region_country_profile.php');
// ********************** Locations end *******************************************

        // User locale options
        if ( $reg->getLocale() == '')
        {
            $data['locale_options'] .= '<option value="0" selected="selected">'.$this->lang['LANG_SELECT'].'</option>';
            $data['locale_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        }

        $api_data = array();
        $rows = $this->utils->get_from_lang_api( '/api/get_active_langs', $api_data );

        foreach ( $rows as $row )
        {
            $lang_name = $this->utils->getLangName($row['code_2a'], $this->session->getLanguageCode2a());
            $data['locale_options'] .= '<option value="'.$row['code_2a'].'"'.(($reg->getLocale() == $row['code_2a'])? ' selected="selected" ' : '').'>'.$lang_name.'</option>';
        }

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose($this->myfile);
        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/userForm.html.twig', array(
            'reg' => $reg->getReg(),
            'user_profile' => $userProfile->getReg(),
            'user_notes' => $user_notes_total,
            //********* File treatment start ******************
            'files' => $files,
            //********* File treatment end ******************
            'filters' => $this->list_filters,
            'pagination' => $this->pagination,
            'data' => $data,
            'cancel' => '/'.$this->folder.'/'.$this->lang['USERS_LINK'],
        ));
    }
}
