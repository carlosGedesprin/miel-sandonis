<?php

namespace src\controller\entity;

use src\controller\baseController;

use DateTime;
use DateTimeZone;

class paymentTransactionController extends baseController
{
    use repository\paymentTransactionRepositoryController;

    private $table = 'payment_transaction';
    private $reg = array(
                            'id'              => '',
                            'account'         => NULL,
                            'account_payment_method' => NULL,
                            'origin'          => NULL,
                            'quote'           => NULL,
                            'funding'         => NULL,
                            'date_reg'        => NULL,
                            'payment_type'    => NULL,
                            'result'          => NULL,
                            'event_id'        => NULL,
                            'origin_id'       => NULL,
                            'transaction_id'  => NULL,
                            'transaction'     => NULL,
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
     * Get bot from his id
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
     * Get bot from key
     *
     */
    public function getRegbyKey( $transaction_id )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Transaction id ==========> ('.$transaction_id.')'.PHP_EOL; fwrite($this->myfile, $txt);
        if ( !$transaction_id ) return false;

        $filter = array( 'transaction_id' => $transaction_id );
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
    public function setAccountPaymentMethod( $account_payment_method ) { $this->reg['account_payment_method'] = $account_payment_method;  }
    public function setOrigin( $origin ) { $this->reg['origin'] = $origin;  }
    public function setQuote( $quote ) { $this->reg['quote'] = $quote;  }
    public function setFunding( $funding ) { $this->reg['funding'] = $funding;  }
    public function setDateReg( $date_reg ) { $this->reg['date_reg'] = $date_reg;  }
    public function setPaymentType( $payment_type ) { $this->reg['payment_type'] = $payment_type;  }
    public function setResult( $result ) { $this->reg['result'] = $result;  }
    public function setEventId( $event_id ) { $this->reg['event_id'] = $event_id;  }
    public function setOriginId( $origin_id ) { $this->reg['origin_id'] = $origin_id;  }
    public function setTransactionId( $transaction_id ) { $this->reg['transaction_id'] = $transaction_id;  }
    public function setTransaction( $transaction ) { $this->reg['transaction'] = $transaction;  }

    public function getReg() { return $this->reg; }
    public function getAll( $filter='', $extra='' ) { return $this->db->fetchAll( $this->table, '*', $filter, $extra ); }
    public function getId() { return $this->reg['id']; }
    public function getAccount() { return $this->reg['account']; }
    public function getAccountPaymentMethod() { return $this->reg['account_payment_method']; }
    public function getOrigin() { return $this->reg['origin']; }
    public function getQuote() { return $this->reg['quote']; }
    public function getFunding() { return $this->reg['funding']; }
    public function getDateReg() { return $this->reg['date_reg']; }
    public function getPaymentType() { return $this->reg['payment_type']; }
    public function getResult() { return $this->reg['result']; }
    public function getEventId() { return $this->reg['event_id']; }
    public function getOriginId() { return $this->reg['origin_id']; }
    public function getTransactionId() { return $this->reg['transaction_id']; }
    public function getTransaction() { return $this->reg['transaction']; }

    /**
     *
     * Change special fields after load from database
     */
    private function loadSpecialFields()
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $this->reg['date_reg'] = ( empty($this->reg['date_reg']) )? NULL : DateTime::createFromFormat('Y-m-d H:i:s', $this->reg['date_reg'], new DateTimeZone($this->session->config['time_zone']));
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     *
     * Change special fields before save to database
     */
    private function setSpecialFields()
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $this->reg['date_reg'] = ( empty($this->reg['date_reg']) )? NULL : $this->reg['date_reg']->format('Y-m-d H:i:s');

        $this->reg['account'] = ( empty($this->reg['account']) )? NULL : $this->reg['account'];
        $this->reg['quote'] = ( empty($this->reg['quote']) )? NULL : $this->reg['quote'];
        $this->reg['payment_type'] = ( empty($this->reg['payment_type']) )? NULL : $this->reg['payment_type'];
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }
}