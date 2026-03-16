<?php

namespace src\controller;

use \src\controller\baseController;

class ajaxController extends baseController
{
    /**
     * @Route("/ajax/get_regions", name="get_regions")
     */
    public function getregionsAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/ajaxController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = 'ajaxController '.__FUNCTION__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $this->db->query('SELECT id, name FROM loc_regions WHERE country = :country ORDER BY name');
        $this->db->bind(':country', $vars['country']);
        $rows = $this->db->resultset();
        header('Content-type: application/json');
        echo json_encode( $rows );

    }
    /**
     * @Route("/ajax/get_cities", name="get_cities")
     */
    public function getcitiesAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/ajaxController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = 'ajaxController '.__FUNCTION__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $this->db->query('SELECT id, name FROM loc_cities WHERE country = :country AND region = :region ORDER BY name');
        $this->db->bind(':country', $vars['country']);
        $this->db->bind(':region', $vars['region']);
        $rows = $this->db->resultset();
        header('Content-type: application/json');
        echo json_encode( $rows );
    }
    /**
     * @Route("/ajax/get_categories", name="get_categories")
     */
    public function getCategoriesAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/ajaxController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = 'ajaxController '.__FUNCTION__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        
        $sql = 'SELECT id, name, ordinal FROM category WHERE parent = :parent';
        $this->db->query($sql);
        $this->db->bind(':parent', $vars['parent']);
        $rows = $this->db->resultset();
        header('Content-type: application/json');
        echo json_encode( $rows );
    }
    /**
     * @Route("/ajax/get_categories", name="get_categories")
     */
    public function getDirectoryCategoriesAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/ajaxController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = 'ajaxController '.__FUNCTION__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $sql = 'SELECT id, name, ordinal FROM directory_category WHERE parent = :parent';
        $this->db->query($sql);
        $this->db->bind(':parent', $vars['parent']);
        $rows = $this->db->resultset();
        header('Content-type: application/json');
        echo json_encode( $rows );
    }
    /**
     * @Route("/ajax/mail_queue/resend/{id:\d+}", name="resend_email")
     */
    public function resend_emailAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/ajaxController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = 'ajaxController '.__FUNCTION__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($vars, TRUE));

        $reg = $this->db->fetchOne( 'mail_queue', '*', ['id' => $vars['id']]);
//fwrite($this->myfile, print_r($reg, TRUE));
        unset($reg['id']);
        $reg['createdby'] = $vars['user'];
        $reg['createddate'] = (new \DateTime("now", new \DateTimeZone($_ENV['time_zone'])))->format('Y-m-d H:i:s');
        $reg['send'] = (new \DateTime("now", new \DateTimeZone($_ENV['time_zone'])))->format('Y-m-d H:i:s');
        $reg['sent'] = NULL;
//fwrite($myfile, print_r($reg, TRUE));

        $this->db->insertArray('mail_queue', $reg);
        header('Content-type: application/json');
        echo json_encode( ['result' => '1'] );

//$txt = 'ajaxController '.__FUNCTION__.' end ==============================================================='.PHP_EOL; fwrite($myfile, $txt);

    }

}
