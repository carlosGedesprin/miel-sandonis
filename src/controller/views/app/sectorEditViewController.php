<?php

namespace src\controller\views\app;

use \src\controller\baseViewController;

use \src\controller\entity\sectorController;

use DateTime;
use DateTimeZone;

class sectorEditViewController extends baseViewController
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
     * @Route('/app/sector/edit/id', name='app_sector_edit')
     *
     * @param POST
     *
     * @return object    Twig template
     */
    public function edititemAction( $vars )
    {
        $this->logger->info('==============='.__METHOD__.' Sector '.$vars['id'].' | User '.$this->user.' ===================================================');
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/sectorEditViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $form_action = $this->folder.'/sector/editor';

        $this->pagination = $this->utils->request_pagination( $this->pagination );
        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        $reg = new sectorController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $reg_original = new sectorController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $reg->setId( ( isset( $vars['id'] ) )? $vars['id'] : '0' );
        //$reg->setKey( $this->utils->request_var( 'key', '', 'ALL', true) );
        $reg->setName( $this->utils->request_var( 'name', '', 'ALL', true) );
        $reg->setPicture( $this->utils->request_var( 'picture', '', 'ALL') );
        $reg->setDescription( $this->utils->request_var( 'description', '', 'ALL', true) );
        $reg->setSlug( $this->utils->request_var( 'slug', '', 'ALL', true) );
        $reg->setActive( $this->utils->request_var( 'active', '1', 'ALL') );

        $data = array(
            'auth_token'    => ( isset( $_POST['auth_token']) )? $_POST['auth_token'] : $this->utils->generateFormToken($form_action),
            'submit'        => (isset($_POST['btn_submit'])) ? true : false,
            'action'        => ( $reg->getId() == '0' )? 'add' : 'edit',
            'folder_options' => '',
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

        //********* File treatment start ******************
        $temp_path = APP_ROOT_PATH.$this->session->config['temp_images_folder'];
        $temp_images_url = $this->startup->getUrlApp().$this->session->config['temp_images_folder'];
        //c:\xampp\htdocs\proaccesible\web\bundles\framework\documents\sectors\
        $files_folder = DOCUMENT_APP_ROOT_PATH.'/sectors/';

        $files = array(
                        '1' => array (
                            'input_id' => 'picture',
                            'input_name' => 'picture',
                            'input_required' => true,
                            'file_name' => '',
                            'file_extension' => '',
                            'file_allowed_extensions' => array('gif', 'jpeg', 'jpg', 'png', 'pdf'),
                            'file_link' => '',
                            'image_size_height' => '100',
                            'image_size_width' => '100',
                            'image_error_text' => $this->lang['ERR_IMAGE_NEEDED'],
                            'file_entity' => 'reg',
                            'file_entity_method' => 'Picture',
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
//$txt = 'Submit ========>'.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
            //TODO:Carlos CSRF is not well resolved, when ajax is involved to send the whole form, startup destroys $_SESSION
            // so normal submit will find session and will not log out the user
            /*
            $valid = $this->utils->verifyFormToken($form_action, $data['auth_token'], 5000);
            if (!$valid) {
                return $this->twig->render('app/'.$this->session->config['app_skin'].'/common/show_message.html.twig', array(
                    'section' => $this->lang['RAG_DOCUMENT_EDIT'],
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
                        'msg' => sprintf( $this->lang['ERR_NAME_SHORT'], '2' )
                    );
                }
                else if ( strlen( $reg->getName() ) > 100 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['name'],
                        'msg' => sprintf($this->lang['ERR_NAME_LONG'],'100' )
                    );
                }
                else
                {
                    if ( $reg->sectorsWithSameName( $reg->getName(), $reg->getId() ) )
                    {
                        $error_ajax[] = array (
                            'dom_object' => ['name'],
                            'msg' => $this->lang['ERR_NAME_EXISTS'],
                        );
                    }
                }
            }

            if ( empty( $reg->getDescription() ) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['description'],
                    'msg' => $this->lang['ERR_DESCRIPTION_NEEDED'],
                );
            }

            if ( empty( $reg->getSlug() ) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['slug'],
                    'msg' => $this->lang['ERR_SECTOR_SLUG_NEEDED'],
                );
            }
            else
            {
                if ( strlen( $reg->getSlug() ) < 2 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['slug'],
                        'msg' => sprintf( $this->lang['ERR_SECTOR_SLUG_SHORT'], '2' ),
                    );
                }
                else if ( strlen( $reg->getSlug() ) > 100 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['slug'],
                        'msg' => sprintf( $this->lang['ERR_SECTOR_SLUG_LONG'], '100' ),
                    );
                }
                elseif ( $reg->sectorsWithSameSlug( $reg->getSlug(), $reg->getId() ) )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['slug'],
                        'msg' => $this->lang['ERR_SECTOR_SLUG_EXISTS'],
                    );
                }
            }

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
                                    'dom_object' => ['image-holder-'.$i],
                                    'msg' => sprintf($this->lang['ERR_FILE_TOO_BIG'], ($this->session->config['max_size_file_upload'] / 1000000)),
                                );
                            }
                        }
                        else
                        {
//$txt = 'File extension wrong'.PHP_EOL; fwrite($this->myfile, $txt);
                            $error_ajax[] = array (
                                'dom_object' => ['image-holder-'.$i],
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
                        'dom_object' => ['image-holder-'.$i],
                        'msg' => $files[$i]['image_error_text'],
                    );
                }
            }
//$txt = '================= Checking images errors and moving to temp end =============================================='.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
            //********* File treatment end ******************

            if ( !sizeof( $error_ajax ) )
            {
                // Fields with special treatment

                //********* File treatment start ******************
//$txt = '================= Moving images from temp to destiny files folder start =============================================='.PHP_EOL; fwrite($this->myfile, $txt);
                foreach( $files as $i => $file_data )
                {
//$txt = 'Field_files index ('.$i.')'.PHP_EOL; fwrite($this->myfile, $txt);
                    if ( $files[$i]['file_name'] != '' )
                    {
//$txt = 'File name field_files '.$files[$i]['file_name'].'.'.$files[$i]['file_extension'].PHP_EOL; fwrite($this->myfile, $txt);
                        $time = (new DateTime("now", new DateTimeZone($this->session->config['time_zone'])))->format('YmdHis') + $i;

                        $file_on_temp = $temp_path.$files[$i]['file_name'].'.'.$files[$i]['file_extension'];
                        $new_file_name = $reg->getTableName().'_'.$reg->getId().'_'.$time.'.'.$files[$i]['file_extension'];
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
                } // foreach( $files as $i => $file_data )
//$txt = '================= Moving images from temp to files destiny folder end =============================================='.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
                //********* File treatment end ******************

                if ( $data['action'] == 'add' )
                {
//$txt = '========== ADD =========='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Reg =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

                    $reg->persistORL();

                    $reg->setKey( md5( $reg->getId() ) );
                    $reg->persistORL();
                }
                else
                {
//$txt = '========== EDIT =========='.PHP_EOL; fwrite($this->myfile, $txt);
                    if ( !empty( $reg->getDateReg() ) )
                    {
                        $reg->setDateReg( DateTime::createFromFormat('d-m-Y H:i:s', $reg->getDateReg(), new DateTimeZone($this->session->config['time_zone'])));
                    }

                    $reg_original->getRegbyId( $reg->getId() );
                    $reg->setKey( $reg_original->getKey() );

//$txt = 'Reg =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                    $reg->persistORL();
                }

                // Send success to be displayed
                $response['status'] = 'OK';
                $response['msg'] = $this->lang['SECTOR_SAVED'];
                $response['action'] = '/'.$this->folder.'/'.$this->lang['SECTORS_LINK'];
//$txt = 'Response =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($response, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
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
//$txt = 'Not submit ========>'.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
            if ( $data['action'] == 'add' )
            {
//$txt = '========== ADD =========='.PHP_EOL; fwrite($this->myfile, $txt);
                $reg->setDateReg( $now->format('d-m-Y H:i:s') );
            }
            else
            {
//$txt = '========== EDIT =========='.PHP_EOL; fwrite($this->myfile, $txt);
                if ( $reg->getRegbyId( $reg->getId() ) )
                {
//$txt = 'REG =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

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
//$txt = '================= Move from user folder to temp folder end =============================================='.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
                    //********* File treatment end ******************

                }
                else
                { 
                    $_SESSION['alert'] = array(
                                               'type'=>'danger',
                                               'message'=>$this->lang['ERR_SECTOR_NOT_EXISTS']
                    );
                    header('Location: /'.$this->folder.'/'.$this->lang['SECTORS_LINK']);
                    exit();
                }
            }
        }

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//fclose( $this->myfile );
        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/sectorForm.html.twig', array(
            'reg' => $reg->getReg(),
            'filters' => $this->list_filters,
            'pagination' => $this->pagination,
            'data' => $data,
            //********* File treatment start ******************
            'files' => $files,
            //********* File treatment end ******************
            'cancel' => '/'.$this->folder.'/'.$this->lang['SECTORS_LINK'],
        ));
    }
}
