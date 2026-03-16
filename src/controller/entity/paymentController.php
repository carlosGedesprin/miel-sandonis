<?php

namespace src\controller\entity;

use src\controller\baseController;

use DateTime;
use DateTimeZone;

class paymentController extends baseController
{
    use repository\paymentRepositoryController;

    private $table = 'payment';
    private $reg = array(
                        'id'           =>  '',
                        'account'      => NULL,
                        'quote'        => NULL,
                        'funding'      => NULL,
                        'payment_type' => NULL,
                        'instalment'   => NULL,
                        'date'         => NULL,
                        'amount'       => '0',
                        'result'       => NULL,
                        'typeTrans'    => NULL,
                        'idTrans'      => NULL,
                        'codAproval'   => NULL,
                        'codError'     => NULL,
                        'desError'     => NULL,
    );

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
     * Get payment from his id
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
     * Persist to db
     *
     */
    public function persistORL()
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Table ==> ('.$this->table.') User ==> ('.$this->user.')'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->reg, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $this->setSpecialFields();

        if ( $this->reg['id'] != '' && $this->reg['id'] != '0' )
        {
            $this->db->updateArrayORL( $this->table, $this->user, 'id', $this->reg['id'], $this->reg );
//$txt = 'reg updated ==> ORL'.PHP_EOL; fwrite($this->myfile, $txt);
        }
        else
        {
            unset( $this->reg['id'] );
            $this->setId( $this->db->insertArrayORL( $this->table, $this->user, $this->reg ) );
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

        if ( $this->reg['id'] != '' && $this->reg['id'] != '0' )
        {
            $this->db->updateArray( $this->table, 'id', $this->reg['id'], $this->reg );
//$txt = 'reg updated ==> ORL'.PHP_EOL; fwrite($this->myfile, $txt);
        }
        else
        {
            unset( $this->reg['id'] );
            $this->setId( $this->db->insertArray( $this->table, $this->reg ) );
        }

        $this->loadSpecialFields();

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $this->getId();
    }

    /**
     *
     * Delete this record
     *
     */
    public function deleteORL()
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
            $this->db->delete( $this->table, 'id', $this->getId() );
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
            return true;
        }
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return false;
    }

    public function setReg( $reg ) { $this->reg = array_merge( $this->reg, $reg );  }
    public function setId( $id ) { $this->reg['id'] = $id;  }
    public function setAccount( $account ) { $this->reg['account'] = $account;  }
    public function setQuote( $quote ) { $this->reg['quote'] = $quote;  }
    public function setFunding( $funding ) { $this->reg['funding'] = $funding;  }
    public function setPaymentType( $payment_type ) { $this->reg['payment_type'] = $payment_type;  }
    public function setInstalment( $instalment ) { $this->reg['instalment'] = $instalment;  }
    public function setDate( $paydate ) { $this->reg['date'] = $paydate;  }
    public function setAmount( $amount ) { $this->reg['amount'] = $amount;  }
    public function setResult( $result ) { $this->reg['result'] = $result;  }
    public function setTypeTrans( $typetrans ) { $this->reg['typeTrans'] = $typetrans;  }
    public function setIdTrans( $idtrans ) { $this->reg['idTrans'] = $idtrans;  }
    public function setCodAproval( $codaproval ) { $this->reg['codAproval'] = $codaproval;  }
    public function setCodError( $coderror ) { $this->reg['codError'] = $coderror;  }
    public function setDesError( $deserror ) { $this->reg['desError'] = $deserror;  }

    public function getReg() { return $this->reg; }
    public function getAll( $filter='', $extra='' ) { return $this->db->fetchAll( $this->table, '*', $filter, $extra ); }
    public function getId() { return $this->reg['id']; }
    public function getAccount() { return $this->reg['account']; }
    public function getQuote() { return $this->reg['quote']; }
    public function getFunding() { return $this->reg['funding']; }
    public function getPaymentType() { return $this->reg['payment_type']; }
    public function getInstalment() { return $this->reg['instalment']; }
    public function getDate() { return $this->reg['date']; }
    public function getAmount() { return $this->reg['amount']; }
    public function getResult() { return $this->reg['result']; }
    public function getTypeTrans() { return $this->reg['typeTrans']; }
    public function getIdTrans() { return $this->reg['idTrans']; }
    public function getCodAproval() { return $this->reg['codAproval']; }
    public function getCodError() { return $this->reg['codError']; }
    public function getDesError() { return $this->reg['desError']; }

    /**
     *
     * Change special fields after load from database
     */
    private function loadSpecialFields()
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $this->reg['date'] = ( empty($this->reg['date']) )? NULL : DateTime::createFromFormat('Y-m-d H:i:s', $this->reg['date'], new DateTimeZone($this->session->config['time_zone']));
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     *
     * Change special fields before save to database
     */
    private function setSpecialFields()
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $this->reg['date'] = ( empty($this->reg['date']) )? NULL : $this->reg['date']->format('Y-m-d H:i:s');
        $this->reg['quote'] = ( empty($this->reg['quote']) )? NULL : $this->reg['quote'];
        $this->reg['funding'] = ( empty($this->reg['funding']) )? NULL : $this->reg['funding'];
        $this->reg['payment_type'] = ( empty($this->reg['payment_type']) )? NULL : $this->reg['payment_type'];
        $this->reg['account'] = ( empty($this->reg['account']) )? NULL : $this->reg['account'];

        $this->reg['amount'] = ( empty($this->reg['amount']) )? '0' : $this->reg['amount'];
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }
}