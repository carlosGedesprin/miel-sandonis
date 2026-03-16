<?php

namespace src\controller\views\app;

use \src\controller\baseViewController;

use \src\controller\entity\cityController;
use \src\controller\entity\cityNameController;

use \src\controller\entity\langController;
use \src\controller\entity\langNameController;
use \src\controller\entity\langTextController;
use \src\controller\entity\langTextNameController;

use DateTime;
use DateTimeZone;

class cityEditViewController extends baseViewController
{

    private $list_filters = array(
                                    'country_code_2a' => array(
                                        'type' => 'select',
                                        'caption' => '',
                                        'placeholder' => '',
                                        'width' => '0',	// if 0 uses the rest of the row
                                        'value' => '',
                                        'value_previous' => '',
                                        'chain_childs' => 'region_code',
                                        'options' => '',
                                    ),
                                    'region_code' => array(
                                        'type' => 'select',
                                        'caption' => '',
                                        'placeholder' => '',
                                        'width' => '0',	// if 0 uses the rest of the row
                                        'value' => '',
                                        'value_previous' => '',
                                        'chain_childs' => '',
                                        'options' => '',
                                    ),
                                    /*
                                        'city_name' => array(
                                            'type' => 'text',
                                            'caption' => '',
                                            'placeholder' => '',
                                            'width' => '0',	// if 0 uses the rest of the row
                                            'value' => '',
                                            'value_previous' => '',
                                        ),
                                    */
    );
    private $pagination = array(
                                'num_page'       => '1',
                                'order'          => 'id',
                                'order_dir'      => 'ASC',
                                'rpp'            => '',
    );

    private $folder = 'app';

    /**
     * @Route('/app/city/edit/id', name='app_city_edit')
     *
     * @param POST
     *
     * @return object    Twig template
     */
    public function edititemAction( $vars )
    {
         $this->logger->info('==============='.__METHOD__.' City '.$vars['city_code'].' | User '.$this->user.' ===================================================');
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/cityEditViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'POST =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Vars =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($vars, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']) );

        $form_action = $this->folder.'/city/editor';

        $lang = new langController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $lang_name = new langNameController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $lang_text = new langTextController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $lang_text_name = new langTextNameController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $this->pagination = $this->utils->request_pagination( $this->pagination );
        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        $reg = new cityController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        //$reg->setId( ( isset( $vars['id'] ) )? $vars['id'] : '0' );
        $reg->setId( $this->utils->request_var( 'id', '', 'ALL') );
        $reg->setCityCode( $this->utils->request_var( 'city_code', ($vars['city_code'] == '0')? '' : $vars['city_code'], 'ALL') );
        $reg->setCountryCode2a( $this->utils->request_var( 'country_code_2a', '', 'ALL') );
        $reg->setRegionCode( $this->utils->request_var( 'region_code', '', 'ALL') );
        $reg->setFlag( $this->utils->request_var( 'flag', '', 'ALL') );

        $reg_name = new cityNameController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        //$reg_name->setCityCode( $reg->getCityCode() );
        
        $data = array(
                        'auth_token' => ( isset( $_POST['auth_token']) )? $_POST['auth_token'] : $this->utils->generateFormToken($form_action),
                        'submit' => (isset($_POST['btn_submit'])) ? true : false,
                        'action' => ( $reg->getCityCode() == '' )? 'add' : 'edit',
                        //********* Names treatment openges start ******************
                        'names' => array(),
                        //********* Names treatment openges end ******************
                        'country_options' => '',
                        'region_options' => '',
        );
//$txt = 'On start function start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'POST =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Reg =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Data =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'FILES =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_FILES, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'On start function end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        //********* Names treatment openges end ******************
        $langs = $lang->getActiveAndPreActive();
//$txt = 'Active & Preactive langs =====================>'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($langs, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        foreach ( $langs as $lang_key => $lang_value )
        {
            $data['names'][$lang_value['code_2a']]['lang_code_2a'] = $lang_value['code_2a'];
            $data['names'][$lang_value['code_2a']]['city_name'] = $this->utils->request_var_array( 'city_name', $lang_value['code_2a'], '', 'POST', true);
        }
//$txt = 'Data names from form =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($data['names'], TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        //********* Names treatment openges end ******************

        //********* File treatment start ******************
        $temp_path = APP_ROOT_PATH.$this->session->config['temp_images_folder'];
        $temp_images_url = $this->startup->getUrlApp().$this->session->config['temp_images_folder'];
        $files_folder = DOCUMENT_ROOT_PATH.'/cities/';
        $data['upload_max_file_size'] = $this->session->config['max_size_file_upload'];

        $files = array(
            '1' => array (
                'input_id' => 'flag',
                'input_name' => 'flag',
                'input_required' => false,
                'file_name' => '',
                'file_extension' => '',
                'file_allowed_extensions' => array('gif', 'jpeg', 'jpg', 'png', 'pdf'),
                'file_link' => '',
                'image_size_height' => '100',
                'image_size_width' => '100',
                'image_error_text' => $this->lang['ERR_CITY_FLAG_NEEDED'],
                'file_entity' => 'reg',
                'file_entity_method' => 'Flag',
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
//$txt = '================= Getting file names from view end =============================================='.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
        //********* File treatment end ******************

        if ( $data['submit'] )
        {
            //TODO:Carlos CSRF is not well resolved, when ajax is involved to send the whole form, startup destroys $_SESSION
            // so normal submit will find session and will not log out the user
            /*
            $valid = $this->utils->verifyFormToken($form_action, $data['auth_token'], 5000);
            if (!$valid) {
                return $this->twig->render('app/'.$this->session->config['app_skin'].'/common/show_message.html.twig', array(
                    'section' => $this->lang['USER_EDIT'],
                    'alert_type' => 'danger',
                    'title' => $this->lang['WARNING'],
                    'message' => $this->lang['ERR_BAD_TOKEN'],
                    'redirect' => '/logout',
                ));
            }
            */

            $check_reg = $reg->checkEdit( $data['action'] );
//$txt = 'Reg check result -----------------------------------------------------------------------'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($check_reg, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

//$txt = 'Checking names -----------------------------------------------------------------------'.PHP_EOL; fwrite($this->myfile, $txt);
            $check_reg_name = array(
                                    'status' => 'OK',
                                    'msg' => array(),
            );
//$txt = 'Names from form to check--------------------------------------------'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($data['names'], TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
            foreach ( $data['names'] as $key => $value )
            {
//$txt = 'Name to check ====== lang_code_2a ('.$value['lang_code_2a'].') name ('.$value['city_name'].')'.PHP_EOL; fwrite($this->myfile, $txt);
                $reg_name->setCityCode( $reg->getCityCode() );
                $reg_name->setLang2a( $value['lang_code_2a'] );
                $reg_name->setName( $value['city_name'] );
                $check_reg_name_temp = $reg_name->checkEdit( $data['action'] );
//$txt = '    Result from check --------------------------------------------'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($check_reg_name_temp, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

                if ( isset( $check_reg_name_temp ) )
                {
                    if ( $check_reg_name_temp['status'] == 'KO' )
                    {
//$txt = '    is error --------------------------------------------'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($check_reg_name_temp['msg'][0], TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                        $check_reg_name['status'] = 'KO';
                        array_push($check_reg_name['msg'], $check_reg_name_temp['msg'][0]);
                    }
                }
            }
//$txt = 'Check names result -----------------------------------------------------------------------'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($check_reg_name, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);


            //********* File treatment start ******************
//$txt = '================= Checking files errors and moving from view to temp start =============================================='.PHP_EOL; fwrite($this->myfile, $txt);
            if ( !empty($_FILES) )
            {
//$txt = '$_FILES not empty '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_FILES, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                foreach( $files as $i => $file_data )
                {
                    if ( !empty($_FILES['file_input_'.$i]["name"]) )
                    {
//$txt = 'File '.$i.PHP_EOL; fwrite($this->myfile, $txt);
                        $filename = basename($_FILES['file_input_'.$i]["name"]);
                        $filename = pathinfo($filename);
//$txt = 'Image pathinfo properties '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($filename, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

                        $time = (new DateTime("now", new DateTimeZone($this->session->config['time_zone'])))->format('YmdHis');

                        $files[$i]['file_name'] = $filename['filename'].'_'.$time.'_'.$i;
                        $files[$i]['file_extension'] = strtolower($filename['extension']);
//$txt = 'File '.$files[$i]['file_name'].'.'.$files[$i]['file_extension'].PHP_EOL; fwrite($this->myfile, $txt);

                        if ( in_array($files[$i]['file_extension'], $files[$i]['file_allowed_extensions']) )
                        {
                            $file_size = $_FILES['file_input_'.$i]['size'];

//$txt = 'File '.$files[$i]['file_name'].'.'.$files[$i]['file_extension'].' size '.$file_size.PHP_EOL; fwrite($this->myfile, $txt);
                            if ( $file_size < $this->session->config['max_size_file_upload'] )
                            {
                                // If image resize it
                                $file = $_FILES['file_input_'.$i]["tmp_name"];
                                $imgProperties = getimagesize($file);
//$txt = 'Image getimagesize properties '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($imgProperties, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                                /*
                                 * https://www.cluemediator.com/resize-an-image-using-the-gd-library-in-php
                                 * https://code-boxx.com/resize-images-php/
                                                                if ( str_contains( $imgProperties['mime'], 'image' ) )
                                                                {
                                                                    $file_type = $imgProperties[2];

                                                                    if( $file_type == IMAGETYPE_JPEG )
                                                                    {
                                                                        $source = imagecreatefromjpeg( $file );
                                                                        $resizeImg = $this->utils->image_resize( $source, $imgProperties[0], $imgProperties[1], $files[$i]['image_size_width'], $files[$i]['image_size_height']);
                                                                        imagejpeg($resizeImg,$pathToThumbs.$imageName);
                                                                    }
                                                                    elseif ($img_type == IMAGETYPE_PNG ) {
                                                                        $source = imagecreatefrompng($image);

                                                                        $resizeImg = image_resize($source,$imgProperties[0],$imgProperties[1]);
                                                                        imagepng($resizeImg,$pathToThumbs.$imageName);
                                                                    }
                                                                    elseif ($img_type == IMAGETYPE_GIF ) {
                                                                        $source = imagecreatefromgif($image);
                                                                        $resizeImg = image_resize($source,$imgProperties[0],$imgProperties[1]);
                                                                        imagegif($resizeImg,$pathToThumbs.$imageName);
                                                                    }

                                                                }
                                */
                                $tempFilePath = $temp_path.$files[$i]['file_name'].'.'.$files[$i]['file_extension'];

                                $image_extensions = array('gif', 'jpeg', 'jpg', 'png');

                                if ( move_uploaded_file($_FILES['file_input_'.$i]["tmp_name"], $tempFilePath) )
                                {
//$txt = 'File moved to temp as '.$tempFilePath.PHP_EOL; fwrite($this->myfile, $txt);
                                }
                                else
                                {
//$txt = 'File NOT moved to temp as '.$tempFilePath.PHP_EOL; fwrite($this->myfile, $txt);
                                }
                            }
                            else
                            {
//$txt = 'File too big'.PHP_EOL; fwrite($this->myfile, $txt);
                                $error_ajax[] = array (
                                    'dom_object' => ['file-box-'.$i],
                                    'msg' => sprintf($this->lang['ERR_FILE_TOO_BIG'], ($this->session->config['max_size_file_upload'] / 1000000)),
                                );
                            }
                        }
                        else
                        {
//$txt = 'File extension wrong'.PHP_EOL; fwrite($this->myfile, $txt);
                            $error_ajax[] = array (
                                'dom_object' => ['file-box-'.$i],
                                'msg' => $this->lang['ERR_FILE_ONLY_IMAGES_PDF'],
                            );
                        }
                    }
                }
            }

            foreach( $files as $i => $file_data )
            {
                if ( $files[$i]['input_required'] && $files[$i]['file_name'] == '' )
                {
//$txt = 'File required error.'.PHP_EOL; fwrite($this->myfile, $txt);
                    $error_ajax[] = array (
                        'dom_object' => ['file-box-'.$i],
                        'msg' => $files[$i]['image_error_text'],
                    );
                }
            }
//$txt = '================= Checking images errors and moving to temp end =============================================='.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
            //********* File treatment end ******************

            if ( $check_reg['status'] == 'OK' && $check_reg_name['status'] == 'OK' ) //if ( !sizeof( $error_ajax ) )
            {
//$txt = 'No errors =========='.PHP_EOL; fwrite($this->myfile, $txt);
                if ( $data['action'] == 'add' )
                {
//$txt = '========== NEW =========='.PHP_EOL; fwrite($this->myfile, $txt);
                    $reg->persist();
                }
                else
                {
//$txt = '========== EDIT =========='.PHP_EOL; fwrite($this->myfile, $txt);
                    $reg->persist();
                }

                //********* Names treatment openges start ******************
//$txt = 'Names to table =====================>'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Deleting names in table =========='.PHP_EOL; fwrite($this->myfile, $txt);
                //$reg_name->deleteByCityCode( $reg->getCityCode() );

//$txt = 'Names from form =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($data['names'], TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

//$txt = 'Saving to names api =========='.PHP_EOL; fwrite($this->myfile, $txt);
                $rows = $lang->getActiveAndPreActive();
//$txt = 'Langs active and pre-active =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($rows, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                foreach ( $rows as $row )
                {
//$txt = 'Lang to treat ====> ('.$row['code_2a'].')'.PHP_EOL; fwrite($this->myfile, $txt);
                    $reg_name->setId( '' );
                    $reg_name->setCityCode( $reg->getCityCode() );
                    $reg_name->setLang2a( $row['code_2a'] );
                    $reg_name->setName( $data['names'][$row['code_2a']]['city_name'] );
//fwrite($this->myfile, print_r($reg_name->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                    if ( !empty( $reg_name->getName() ) ) $reg_name->persist();
                }
                //********* Names treatment openges end ******************

                //********* File treatment start ******************
//$txt = '================= Moving images from temp to destiny files folder start =============================================='.PHP_EOL; fwrite($this->myfile, $txt);
                foreach( $files as $i => $file_data )
                {
//$txt = 'Field_files index ('.$i.')'.PHP_EOL; fwrite($this->myfile, $txt);
                    if ( $files[$i]['file_name'] != '' )
                    {
//$txt = 'File name field_files '.$files[$i]['file_name'].'.'.$files[$i]['file_extension'].PHP_EOL; fwrite($this->myfile, $txt);
                        $time = (new DateTime("now", new DateTimeZone($this->session->config['time_zone'])))->format('YmdHis') . $i;

                        $file_on_temp = $temp_path.$files[$i]['file_name'].'.'.$files[$i]['file_extension'];
                        $new_file_name = $reg->getTableName().'_'.$reg->getCode2a().'_'.$time.'.'.$files[$i]['file_extension'];
                        $file_on_destiny = $files_folder.$new_file_name;
//$txt = 'File paths '.$file_on_temp.' -> '.$file_on_destiny.PHP_EOL; fwrite($this->myfile, $txt);

                        if ( file_exists( $file_on_temp ) )
                        {
//$txt = 'File exists in temp'.PHP_EOL; fwrite($this->myfile, $txt);
                            if ( file_exists( $file_on_destiny ) )
                            {
//$txt = 'File exists in files folder --> unlink'.PHP_EOL; fwrite($this->myfile, $txt);
                                unlink( $file_on_destiny );
                            }
                            /*
                                                        $original_image = $files_folder.${$files[$i]['file_array_name']}[$files[$i]['file_array_field']];
                            //$txt = 'Original file in files folder is: '.$original_image.PHP_EOL; fwrite($this->myfile, $txt);
                                                        if ( file_exists( $original_image ) && !is_dir( $original_image) )
                                                        {
                            //$txt = 'File original exists in files folder; '.$original_image.' --> unlink'.PHP_EOL; fwrite($this->myfile, $txt);
                                                            unlink($original_image);
                                                        }
                            */
                            if ( copy( $file_on_temp, $file_on_destiny ) )
                            {
                                // Delete temp file
                                unlink( $file_on_temp );

                                $method = 'set'.$files[$i]['file_entity_method'];
//$txt = 'Populating object "'.$files[$i]['file_entity'].'" field "'.$method.'" with ('.$new_file_name.')'.PHP_EOL; fwrite($this->myfile, $txt);
                                ${$files[$i]['file_entity']}->$method( $new_file_name );
//                                ${$files[$i]['file_array_name']}[$files[$i]['file_array_field']] = $new_file_name;
//$txt = 'File moved from '.$file_on_temp.' to --> '.$file_on_destiny.PHP_EOL; fwrite($this->myfile, $txt);
                            }
                            else
                            {
//$txt = 'File NOT moved from '.$file_on_temp.' to --> '.$file_on_destiny.PHP_EOL; fwrite($this->myfile, $txt);
                            } // copy($file_on_temp, $file_on_destiny)
                        } // file_exists( $file_on_temp
                    } // $files[$i]['file_name'] != ''
//$txt = 'Entity '.$files[$i]['file_entity'].' after move files =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r(${$files[$i]['file_entity']}->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                    ${$files[$i]['file_entity']}->persist();
                } // foreach( $files as $i => $file_data )
//$txt = '================= Moving images from temp to files destiny folder end =============================================='.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
                //********* File treatment end ******************

                // Send success to be displayed
                $response['status'] = 'OK';
                $response['msg'] = $this->lang['CITY_SAVED'];
                $response['action'] = '/'.$this->folder.'/cities';
                echo json_encode($response);
                exit();
            }
            else
            {
                // Errors found
//$txt = 'Errors after verifications =========='.PHP_EOL; fwrite($this->myfile, $txt);

                // Renew CSRF
                //$data['auth_token'] = $this->utils->generateFormToken($form_action);

                // Send errors to be displayed
                $response = array (
                                    'status' => 'KO',
                                    'errors' => array(),
                );

//$txt = 'City can not be saved reg -> Status '.$check_reg['status'].PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($check_reg, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'City can not be saved reg_name -> '.$check_reg_name['status'].PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($check_reg_name, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

                $error_key = 0;

                if ( $check_reg['status'] == 'KO' )
                {
//$txt = 'Error msg '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($check_reg['msg'], TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

                    foreach ( $check_reg['msg'] as $key => $value )
                    {
//$txt = 'Error key ('.$key.')'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Value'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($value, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

                        $error = $value[0];
//$txt = 'Error '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($error, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

                        $response['errors'][$error_key] = array (
                                                                'dom_object' => NULL,
                                                                'msg' => ''
                                                       );

                        if ( $error['msg'][1] != '' )
                        {
                            $response['errors'][$error_key]['msg'] = sprintf($this->lang[$error['msg'][0]], $error['msg'][1]);
                        }
                        else
                        {
                            $response['errors'][$error_key]['msg'] = $this->lang[$error['msg'][0]];
                        }

                        $error_key++;
                    }
                }
//$txt = 'Error total reg errors -----------------------------------------------------------------'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($response, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

                //$last_key = intval( $key ) + 1;
//$txt = 'Error Last key ('.$last_key.')'.PHP_EOL; fwrite($this->myfile, $txt);

                if ( $check_reg_name['status'] == 'KO' )
                {
//$txt = 'Error msg names'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($check_reg_name['msg'], TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

                    foreach ( $check_reg_name['msg'] as $key => $value )
                    {
//$txt = 'Error key ('.$key.')'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Value'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($value, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

                        $error = $value[0];
//$txt = 'Error '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($error, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

                        $response['errors'][$error_key] = array (
                                                                    'dom_object' => NULL,
                                                                    'msg' => ''
                        );

                        if ( $error['msg'][1] != '' )
                        {
//$txt = 'Error msg on sprintf'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($error['msg'], TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                            $lang->getRegbyCode2a( $this->session->getLanguageCode2a() );
                            $lang_name->getRegbyCodeAndLang( $lang->getCode2a(), $this->session->getLanguageCode2a() );
                            $lang_text->getRegbyLangKey( $error['msg'][1] );
                            $lang_text_name->getRegbyLangTextAndLang( $lang_text->getLangKey(), $this->session->getLanguageCode2a() );
                            /*
                            $api_data = array(
                                                'lang_code_2a' => $error['msg'][1],
                                                'lang_2a' => $this->session->getLanguageCode2a()
                            );
                            $lang_name = $this->utils->get_from_lang_api( '/api/get_lang_name', $api_data );
//$txt = 'Lang name ========== Lang code 2a ('.$api_data['lang_code_2a'].') code 2a ('.$api_data['lang_2a'].')'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($lang_name, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);


                            if ( $lang_name && strtoupper( $lang_name['status'] ) == 'OK' )
                            {
                                $lang_name = $lang_name['msg']['name'];
                            }
                            else
                            {
                                $lang_name = $lang_name['msg']['lang_code_2a'];
                            }
                            */

                            $response['errors'][$error_key]['msg'] = sprintf($this->lang[$error['msg'][0]], $lang_name->getName());
                            //$response['errors'][$error_key]['msg'] = sprintf($this->lang[$error['msg'][0]], $error['msg'][1]);
                        }
                        else
                        {
                            $response['errors'][$error_key]['msg'] = $this->lang[$error['msg'][0]];
                        }

                        $error_key++;
                    }
                }
            }
//$txt = 'Error all all errors -----------------------------------------------------------------------'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($response, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
            echo json_encode($response);
            exit();
        }
        else
        {
            if ( $data['action'] == 'add' )
            {
//$txt = '========== NEW =========='.PHP_EOL; fwrite($this->myfile, $txt);

                //********* Names treatment openges start ******************
//$txt = 'Names from database =====================>'.PHP_EOL; fwrite($this->myfile, $txt);
                $langs = $lang->getActiveAndPreActive();
//$txt = 'Active & Preactive langs =====================>'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($langs, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                foreach ( $langs as $lang_key => $lang_value )
                {
//$txt = 'Lang to treat ('.$lang_value['code_2a'].')'.PHP_EOL; fwrite($this->myfile, $txt);
                    $data['names'][$lang_value['code_2a']]['lang_code_2a'] = $lang_value['code_2a'];
                    $data['names'][$lang_value['code_2a']]['city_name'] = '';
                }
                //********* Names treatment openges end ******************
            }
            else
            {
//$txt = '========== EDIT =========='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Reg to edit =========> '.$reg->getCityCode().PHP_EOL; fwrite($this->myfile, $txt);

                if ( $reg->getRegbyCityCode( $reg->getCityCode() ) )
                {
//$txt = 'Reg to edit =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                    //********* Names treatment openges start ******************
//$txt = 'Names from database =====================>'.PHP_EOL; fwrite($this->myfile, $txt);
                    $langs = $lang->getActiveAndPreActive();
//$txt = 'Active & Preactive langs =====================>'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($langs, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                    foreach ( $langs as $lang_key => $lang_value )
                    {
//$txt = 'Lang to treat ('.$lang_value['code_2a'].')'.PHP_EOL; fwrite($this->myfile, $txt);
                        $data['names'][$lang_value['code_2a']]['lang_code_2a'] = $lang_value['code_2a'];
                        $reg_name->getRegbyCityCodeAndLang2a( $reg->getCityCode(), $lang_value['code_2a'] );
//$txt = 'City '.$reg->getCityCode().' name in ('.$lang_value['code_2a'].') is ('.$reg_name->getName().')'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg_name->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                        $data['names'][$lang_value['code_2a']]['city_name'] = $reg_name->getName();
                    }
//$txt = 'Names in data names from db =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($data['names'], TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                    //********* Names treatment openges end ******************

                    //********* File treatment start ******************
//$txt = '================= Getting file names from database and populating files array start =============================================='.PHP_EOL; fwrite($this->myfile, $txt);
                    foreach( $files as $i => $file_data )
                    {
//$txt = 'Entity method for file '.$i.': '.$files[$i]['file_entity'].'->get'.$files[$i]['file_entity_method'].PHP_EOL; fwrite($this->myfile, $txt);
                        $method = 'get'.$files[$i]['file_entity_method'];
//$txt = 'Name on record ('.${$files[$i]['file_entity']}->$method().')'.PHP_EOL; fwrite($this->myfile, $txt);
                        if ( ${$files[$i]['file_entity']}->$method() != '' )
                        {
                            $file_temp = explode('.', ${$files[$i]['file_entity']}->$method());
                            $files[$i]['file_name'] = $file_temp[0];
                            $files[$i]['file_extension'] = $file_temp[1];
                            unset( $file_temp );
//$txt = 'Image name $files array '.$files[$i]['file_name'].'.'.$files[$i]['file_extension'].PHP_EOL; fwrite($this->myfile, $txt);
                        }
                    }
//$txt = '================= Getting file names from database and populating files array end =============================================='.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
                    //********* File treatment end ******************

                    //********* File treatment start ******************
//$txt = '================= Move from destiny folder to temp folder start =============================================='.PHP_EOL; fwrite($this->myfile, $txt);
                    foreach( $files as $i => $value)
                    {
//$txt = 'field_files '.$i.':'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($files[$i], TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
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
                                $files[$i]['file_link'] = $temp_images_url.$files[$i]['file_name'].'.'.$files[$i]['file_extension'].'?t='.time();
                            }
                            else
                            {
//$txt = 'Source image not exists'.PHP_EOL; fwrite($this->myfile, $txt);
                                $files[$i]['file_name'] = $files[$i]['file_extension'] = '';
                            }
                        } // $files[$i]['file_name'] != ''
                        else
                        {
//$txt = 'No source image on array'.PHP_EOL; fwrite($this->myfile, $txt);
                        }
//$txt = 'Image treated - '.$temp_images_url.$files[$i]['file_name'].'.'.$files[$i]['file_extension'].PHP_EOL; fwrite($this->myfile, $txt);
                    } // foreach( $files as $i => $value)
//$txt = '================= Move from destiny folder to temp folder end =============================================='.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
                    //********* File treatment end ******************
                }
                else
                {
                    $_SESSION['alert'] = array(
                        'type'          => 'danger',
                        'message'       => $this->lang['ERR_CITY_NOT_EXISTS'],
                        'filters'       => $this->list_filters,
                        'pagination'    => $this->pagination,
                    );
                    header('Location: /'.$this->folder.'/cities');
                    exit;
                }
            }
        }

        // Country select options list
        require_once(APP_ROOT_PATH.'/src/util/view_selects/city_country_region_all.php');

        // Region select options list
        require_once(APP_ROOT_PATH.'/src/util/view_selects/region_country_code2a.php');

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose($this->myfile);
        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/cityForm.html.twig', array(
            'reg' => $reg->getReg(),
            //********* File treatment start ******************
            'files' => $files,
            //********* File treatment end ******************
            'filters' => $this->list_filters,
            'pagination' => $this->pagination,
            'data' => $data,
            'cancel' => '/'.$this->folder.'/cities',
        ));
    }
}
