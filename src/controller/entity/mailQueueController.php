<?php

namespace src\controller\entity;

use \src\controller\baseController;

use DateTime;
use DateTimeZone;

class mailQueueController extends baseController
{
    use repository\mailQueueRepositoryController;

    private $table = 'mail_queue';
    private $reg = array(
                            'id'            => '',
                            'send'          => NULL,
                            'priority'      => NULL,
                            'sent'          => NULL,
                            'to_address'    => NULL,
                            'to_name'       => NULL,
                            'cc_address'    => NULL,
                            'cc_name'       => NULL,
                            'bcc_address'   => NULL,
                            'bcc_name'      => NULL,
                            'from_address'  => NULL,
                            'from_name'     => NULL,
                            'template'      => NULL,
                            'process'       => NULL,
                            'subject'       => NULL,
                            'pre_header'    => NULL,
                            'locale'        => NULL,
                            'message'       => NULL,
                            'headers'       => NULL,
                            'images'        => NULL,
                            'assign_vars'   => NULL,
                            'block_name'    => NULL,
                            'assign_block_vars' => NULL,
                            'attached'      => NULL,
                            'token'         => NULL,
                        );

    private $images = array();
    private $assign_vars = array();
    private $attached = array();

    public function __construct( $params )
    {
        parent::__construct( $params );
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'session locale ('.$this->session->getLanguageCode2a().')'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'image header ('.$this->session->config['email_header_image'].')'.PHP_EOL; fwrite($this->myfile, $txt);
        $this->addImage( 'img_header', $this->session->config['email_header_image'] );
//$txt = 'Array images '.PHP_EOL; fwrite($this->myfile, print_r( $this->images, $txt) );
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     *
     * Get table name
     *
     */
    public function getTableName()
    {
        return $this->table;
    }

    /**
     *
     * Get mailqueue from his id
     *
     */
    public function getRegbyId( $id )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Id ==========> ('.$id.')'.PHP_EOL; fwrite($this->myfile, $txt);
        if ( !$id ) return false;

        $filter = array( 'id' => $id  );
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $this->getRegFromDB( $filter );
    }

    /**
     *
     * Get reg
     *
     */
    private function getRegFromDB( $filter )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Filter ==========> ('.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($filter, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        if ( $item = $this->db->fetchOne( $this->table, '*', $filter ) )
        {
            $this->reg = array_merge( $this->reg, $item );

            $this->loadSpecialFields();

//$txt = 'reg found==========> ('.$this->getId().')'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
            return true;
        }
        else
        {
//$txt = 'reg NOT found ==========> '.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
            return false;
        }
    }

    /**
     *
     * Persist to db and orl
     *
     */
    public function persistORL()
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Table ==> ('.$this->table.') User ==> ('.$this->user.')'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->reg, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $this->setSpecialFields();

        $now = ( new DateTime('now', new DateTimeZone($this->session->config['time_zone'])) )->format('Y-m-d H:i:s');

        if ( $this->getSend() == '' ) {  $this->addMailField('send', $now); }
        if ( $this->getPriority() == '' ) { $this->addMailField('priority', '3');  }
        //if ( $this->getSent() == '' ) { $this->reg['sent'];  }
        if ( $this->getFromAddress() == '' ) {  $this->addMailField('from_address', $this->session->config['email_system_address']); }
        if ( $this->getFromName() == '' ) { $this->addMailField('from_name', $this->session->config['email_system_name']);  }

//        if ( !isset($this->assign_vars['name']) ) { $this->addAssignVar( 'name' , $this->getToName() );  }
//        if ( !isset($this->assign_vars['email']) ) { $this->addAssignVar( 'email' , $this->getToAddress() );  }

        $this->setImages();
        $this->setAssignVars();

        if ( !empty( $this->attached ) ) $this->setAttached();

        if ( $this->reg['id'] != '' && $this->reg['id'] != '0' )
        {
            $this->db->updateArrayORL( $this->table, $this->user, 'id', $this->reg['id'], $this->reg );
//$txt = 'reg updated ==> ORL'.PHP_EOL; fwrite($this->myfile, $txt);
        }
        else
        {
            unset( $this->reg['id'] );
            $this->setId( $this->db->insertArrayORL( $this->table, $this->user, $this->reg ) );
//$txt = 'reg inserted ==> ORL'.PHP_EOL; fwrite($this->myfile, $txt);
        }

        $this->loadSpecialFields();

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $this->getId();
    }

    /**
     *
     * Persist to db
     *
     */
    public function persist()
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Table ==> ('.$this->table.') User ==> ('.$this->user.')'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->reg, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $this->setSpecialFields();

        $now = (new DateTime('now', new DateTimeZone($this->session->config['time_zone'])))->format('Y-m-d H:i:s');

        if ( $this->getSend() == '' ) {  $this->addMailField('send', $now); }
        if ( $this->getPriority() == '' ) { $this->addMailField('priority', '3');  }
        //if ( $this->getSent() == '' ) { $this->reg['sent'];  }
        if ( $this->getFromAddress() == '' ) {  $this->addMailField('from_address', $this->session->config['email_system_address']); }
        if ( $this->getFromName() == '' ) { $this->addMailField('from_name', $this->session->config['email_system_name']);  }

        if ( !isset($this->assign_vars['name']) ) { $this->addAssignVar( 'name' , $this->getToName() );  }
        if ( !isset($this->assign_vars['email']) ) { $this->addAssignVar( 'email' , $this->getToAddress() );  }

        $this->setImages();
        $this->setAssignVars();

        if ( !empty( $this->attached ) ) $this->setAttached();

        if ( $this->reg['id'] != '' && $this->reg['id'] != '0' )
        {
            $this->db->updateArray( $this->table, 'id', $this->reg['id'], $this->reg );
//$txt = 'reg updated ==> ORL'.PHP_EOL; fwrite($this->myfile, $txt);
        }
        else
        {
            unset( $this->reg['id'] );
            $this->setId( $this->db->insertArray( $this->table, $this->reg ) );
//$txt = 'reg inserted ==> ORL'.PHP_EOL; fwrite($this->myfile, $txt);
        }

        $this->loadSpecialFields();

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $this->getId();
    }

    public function addImage( $name, $value )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $this->images[$name] = $value;
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }

    public function addAssignVar( $name, $value )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $this->assign_vars[$name] = $value;
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }

    public function addMailField( $name, $value )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $this->reg[$name] = $value;
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }

    public function addAttached( $name, $value )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $this->attached[$name] = $value;
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     *
     * Delete this record
     *
     */
    public function delete()
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Table ==========> ('.$this->table.')'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->reg, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        if ( $this->reg['id'] != '' && $this->reg['id'] != '0' )
        {
                $this->db->deleteORL( $this->table, $this->user, 'id', $this->getId() );
//$txt = 'reg deleted ==========> '.$this->reg['id'].PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
                return true;
            }
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return false;
    }

    public function setReg( $reg ) { $this->reg = array_merge( $this->reg, $reg );  }
    public function setId( $id ) { $this->reg['id'] = $id; }
    public function setSend( $send ) { $this->reg['send'] = $send; }
    public function setPriority( $priority ) { $this->reg['priority'] = $priority; }
    public function setSent( $sent ) { $this->reg['sent'] = $sent; }
    public function setToAddress( $toaddress ) { $this->reg['to_address'] = $toaddress; }
    public function setToName( $toname ) { $this->reg['to_name'] = $toname; }
    public function setCcAddress( $ccaddress ) { $this->reg['cc_address'] = $ccaddress; }
    public function setCcName( $ccname ) { $this->reg['cc_name'] = $ccname; }
    public function setBccAddress( $bccaddress ) { $this->reg['bcc_address'] = $bccaddress; }
    public function setBccName( $bccname ) { $this->reg['bcc_name'] = $bccname; }
    public function setFromAddress( $fromaddress ) { $this->reg['from_address'] = $fromaddress; }
    public function setFromName( $fromname ) { $this->reg['from_name'] = $fromname; }
    public function setTemplate( $template ) { $this->reg['template'] = $template; }
    public function setProcess( $process ) { $this->reg['process'] = $process; }
    public function setSubject( $subject ) { $this->reg['subject'] = $subject; }
    public function setPreheader( $preheader ) { $this->reg['pre_header'] = $preheader; }
    public function setLocale( $locale ) { $this->reg['locale'] = $locale; }
    public function setMessage( $message ) { $this->reg['message'] = $message; }
    public function setHeaders( $headers ) { $this->reg['headers'] = $headers; }

    public function setImages()
    {  
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $this->reg['images'] = serialize( $this->images );
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }
    
    public function setAssignVars() 
    { 
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $this->reg['assign_vars'] = serialize($this->assign_vars);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }
    
    public function setBlockName( $blockname ) { $this->reg['block_name'] = $blockname;  }
    public function setAssignBlockVars( $assingblockvars ) { $this->reg['assign_block_vars'] = $assingblockvars;  }
    
    public function setAttached() 
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $this->reg['attached'] = serialize( $this->attached );
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }

    public function setToken( $token ) { $this->reg['token'] = $token;  }

    public function getReg() { return $this->reg; }
    public function getAll( $filter='', $extra='' ) { return $this->db->fetchAll( $this->table, '*', $filter, $extra ); }
    public function getId() { return $this->reg['id']; }
    public function getSend() { return $this->reg['send']; }
    public function getPriority() { return $this->reg['priority']; }
    public function getSent() { return $this->reg['sent']; }
    public function getToAddress() { return $this->reg['to_address']; }
    public function getToName() { return $this->reg['to_name']; }
    public function getCcAddress() { return $this->reg['cc_address']; }
    public function getCcName() { return $this->reg['cc_name']; }
    public function getBccAddress() { return $this->reg['bcc_address']; }
    public function getBccName() { return $this->reg['bcc_name']; }
    public function getFromAddress() { return $this->reg['from_address']; }
    public function getFromName() { return $this->reg['from_name']; }
    public function getTemplate() { return $this->reg['template']; }
    public function getProcess() { return $this->reg['process']; }
    public function getSubject() { return $this->reg['subject']; }
    public function getPreHeader() { return $this->reg['pre_header']; }
    public function getMessage() { return $this->reg['message']; }
    public function getLocale() { return $this->reg['locale']; }
    public function getHeaders() { return $this->reg['headers']; }
    public function getImages() { return $this->images; }
    public function getAssignVars() { return $this->assign_vars; }
    public function getBlockName() { return $this->reg['block_name']; }
    public function getAssignBlockVars() { return $this->reg['assign_block_vars']; }
    public function getAttached() { return $this->attached; }
    public function getToken() { return $this->reg['token']; }

    /**
     *
     * Change special fields after load from database
     */
    private function loadSpecialFields()
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $this->reg['send'] = ( empty($this->reg['send']) )? NULL : DateTime::createFromFormat('Y-m-d H:i:s', $this->reg['send'], new DateTimeZone($this->session->config['time_zone']));
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     *
     * Change special fields before save to database
     */
    private function setSpecialFields()
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $this->reg['send'] = ( empty($this->reg['send']) )? NULL : $this->reg['send']->format('Y-m-d H:i:s');
        if ( empty( $this->getSent() ) ) $this->setSent( NULL );

        if ( empty( $this->getLocale() ) ) $this->setLocale( $this->session->getLanguageCode2a() );
//$txt = 'Locale ('.$this->getLocale().')'.PHP_EOL; fwrite($this->myfile, $txt);
        $this->setLanguageforSpecialVars();
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }
}