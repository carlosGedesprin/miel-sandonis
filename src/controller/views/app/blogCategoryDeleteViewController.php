<?php

namespace src\controller\views\app;

use \src\controller\baseViewController;

use \src\controller\entity\blogCategoryController;
use \src\controller\entity\blogArticleController;

class blogCategoryDeleteViewController extends baseViewController
{
    private $list_filters = array(
                                    'title' => array(
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
     * @Route("/app/blog_category/delete/id", name="app_blog_category_delete")
     *
     * @param $vars array GET variables
     *
     * @return object    Twig template
     */
    public function deleteitemAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/blogCategoryDeleteViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $form_action = $this->folder.'/blog_category/delete';

        $this->pagination = $this->utils->request_pagination( $this->pagination );
        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        if ( !isset( $vars['id']) || $vars['id'] == '' || $vars['id'] == '0' )
        {
            $_SESSION['alert'] = array(
                'type'          => 'danger',
                'message'       => $this->lang['ERR_BLOG_CATEGORY_NOT_EXISTS'],
                'filters'       => $this->list_filters,
                'pagination'    => $this->pagination,
            );
            header('Location: /'.$this->folder.'/'.$this->lang['BLOG_CATEGORIES_LINK']);
            exit;
        }

        $reg = new blogCategoryController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $blog_article = new blogArticleController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $reg->getRegbyId( $vars['id'] );

        $data = array(
            'auth_token' => ( isset( $_POST['auth_token']) )? $_POST['auth_token'] : $this->utils->generateFormToken($form_action),
            'submit' => (isset($_POST['btn_submit'])) ? true : false,
            'action' => 'delete',
        );

        $error_ajax = array();

        //********* File treatment start ******************
        $temp_path = APP_ROOT_PATH.$this->session->config['temp_images_folder'];
        $temp_images_url = $this->startup->getUrlApp().$this->session->config['temp_images_folder'];
        $files_folder = DOCUMENT_ROOT_PATH.'/blog/';
        $data['upload_max_file_size'] = $this->session->config['max_size_file_upload'];

        $files = array(
            '1' => array (
                'input_id' => 'blog_category_picture',
                'input_name' => 'picture',
                'input_required' => true,
                'file_name' => '',
                'file_extension' => '',
                'file_allowed_extensions' => array('gif', 'jpeg', 'jpg', 'png', 'pdf'),
                'file_link' => '',
                'image_size_height' => '100',
                'image_size_width' => '100',
                'image_error_text' => $this->lang['ERR_BLOG_CATEGORY_IMAGE_NEEDED'],
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
//$txt = '================= Getting file names from view end =============================================='.PHP_EOL; fwrite($this->myfile, $txt);
        //********* File treatment end ******************

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
            $blog_articles = $blog_article->getAll();
            if ( sizeof( $blog_articles ) )
            {
                $error_ajax[] = array (
                    'dom_object' => [],
                    'msg' => $this->lang['ERR_BLOG_CATEGORY_HAS_ARTICLES'],
                );
            }

            if ( !sizeof( $error_ajax ) )
            {
                $this->logger->info('==============='.__METHOD__.' Blog category '.$vars['id'].' | User '.$this->user.' ===================================================');

                $reg->delete();

                //********* File upload treatment start ******************
//$txt = '================= Delete from destiny folder start =============================================='.PHP_EOL; fwrite($this->myfile, $txt);
                foreach( $files as $i => $value)
                {
//$txt = 'field_files '.$key.':'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($files[$key], TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                    if ( $files[$i]['file_name'] != '' )
                    {
//$txt = 'Delete image ('.$i.')'.PHP_EOL; fwrite($this->myfile, $txt);
                        $destinyFilePath = $files_folder . $files[$i]['file_name'] . '.' . $files[$i]['file_extension'];
                        if ( file_exists( $destinyFilePath ) )
                        {
                            unlink( $destinyFilePath );
                        }
                    }
                    else
                    {
//$txt = 'No source image on array'.PHP_EOL; fwrite($this->myfile, $txt);
                    }
                } // foreach( $files as $i => $value)
//$txt = '================= Delete from destiny folder end =============================================='.PHP_EOL; fwrite($this->myfile, $txt);
                //********* File upload treatment end ******************

                $this->pagination['num_page'] = '1';

                // Send success to be displayed
                $response['status'] = 'OK';
                $response['msg'] = $this->lang['BLOG_CATEGORY_DELETED'];
                $response['action'] = '/'.$this->folder.'/blog_categories';
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
//$txt = '================= Move from destiny folder to temp folder end =============================================='.PHP_EOL; fwrite($this->myfile, $txt);
                //********* File treatment end ******************

                // Fields with special treatment
//fwrite($this->myfile, print_r($reg->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
            }
            else
            {
                $_SESSION['alert'] = array('type'=>'danger', 'message'=>$this->lang['ERR_BLOG_CATEGORY_NOT_EXISTS']);
                header('Location: /'.$this->folder.'/'.$this->lang['BLOG_CATEGORIES_LINK']);
                exit();
            }
        }

//$txt = '======================'.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose($this->myfile);
        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/blog_categoryForm.html.twig', array(
            'reg' => $reg->getReg(),
            'filters' => $this->list_filters,
            'pagination' => $this->pagination,
            'data' => $data,
            //********* File treatment start ******************
            'files' => $files,
            //********* File treatment end ******************
            'cancel' => '/'.$this->folder.'/blog_categories',
        ));
    }
}
