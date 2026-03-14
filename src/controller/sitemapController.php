<?php

namespace src\controller;

use \src\controller\baseController;

class sitemapController extends baseController
{
    /**
     * @Route("/sitemap.xml", name="home")
     */
    public function sitemapAction()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/sitemapController'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = 'sitemapController '.__FUNCTION__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $now = (new \DateTime('now', new \DateTimeZone($_ENV['time_zone'])));

        //require_once( APP_ROOT_PATH.'/src/controller/baseController.php');

        //require_once( APP_ROOT_PATH.'/src/controller/entity/sitemapController.php');
        //$reg = new \src\controller\entity\sitemapController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
//http://www.danielazucotti.com/conocimientos/generar-sitemap-xml-con-php-de-forma-dinamica/

        $xml = '<?xml version="1.0" encoding="UTF-8"?>
                    <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        $rows = $this->db->fetchAll('site_map', '*', ['active' => '1'], 'ORDER BY createddate');
        foreach ( $rows as $row )
        {
            $createddate = \DateTime::createFromFormat('Y-m-d', $row['createddate'], new \DateTimeZone($_ENV['time_zone']));

            $xml .= '<url>
                <loc>'.$_ENV['protocol'].'://'.$_ENV['domain'].(( $row['slug'] != '/' )? $row['slug'] : '').'</loc>
                <lastmod>'.$createddate->format('Y-m-d').'</lastmod>
                <changefreq>'.$row['changefreg'].'</changefreq>
                <priority>'.$row['priority'].'</priority>
            </url>';
        }
        $xml .='</urlset>';

        header('Content-type:text/xml;charset:utf8');
        echo $xml;

//$txt = 'sitemapController '.__FUNCTION__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//        $data = array();
//        return $this->twig->render('web/'.$this->session->config['website_skin'].'/index.html.twig', array(
//            'data' => $data,
//        ));

    }}
