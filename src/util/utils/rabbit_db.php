<?php
namespace src\util\utils;

/**
 * Trait rabbit_db
 * @package Utils
 */
trait rabbit_db
{
    protected $dbConnectionRabbit;
    private $stmt;

    private $myfile;

    /**
     * Creates the pdo connection
     */
    public function openDatabaseRabbit()
    {
$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/util_'.__FUNCTION__.'.txt', 'a+') or die('Unable to open file!');
//$txt = 'db '.__FUNCTION__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Connection'.print_r( $this->dbConnectionRabbit, true ).PHP_EOL; fwrite($this->myfile, $txt);
        // Set DSN
        $dsn = 'mysql:host='.$_ENV['dbra_host'].';port='.$_ENV['dbra_port'].';dbname='.$_ENV['dbra_dbname'].';charset=utf8';
        // Set options
        $options = array(
            \PDO::ATTR_PERSISTENT    => true,
            \PDO::ATTR_ERRMODE       => \PDO::ERRMODE_EXCEPTION,    //Throw exceptions.
        );
        // Create a new PDO instanace
         try
        { 
            if ( empty( $this->dbConnectionRabbit ) ) {
                $this->dbConnectionRabbit = new \PDO($dsn, $_ENV['dbra_user'], $_ENV['dbra_password'], $options);
            }
        }
        catch(\PDOException $e)
        {
            $txt = ('*** Start *****************************************************************').PHP_EOL; fwrite($this->myfile, $txt);
            $txt = ('** '.__METHOD__).PHP_EOL; fwrite($this->myfile, $txt);
            $txt = ('*************************************************************************').PHP_EOL; fwrite($this->myfile, $txt);
            $txt = ('PDO Error connection ('.$e->getMessage().')').PHP_EOL; fwrite($this->myfile, $txt);
            $txt = ('*************************************************************************').PHP_EOL; fwrite($this->myfile, $txt);
            $txt = ('Database: '.$dsn.' User: '.$_ENV['dbra_user'].'Pass: '.$_ENV['dbra_password']).PHP_EOL; fwrite($this->myfile, $txt);
            $txt = ('*** End *****************************************************************').PHP_EOL; fwrite($this->myfile, $txt);
        }
    }

    /**
     * Inteacts with table based on a sql sentece
     *
     * @param $sql
     * @return array/bool   True if insert or update and Array in the other cases
     */
    public function querySQLRabbit($sql)
    {
//$txt = 'db '.__FUNCTION__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);//$e = new \Exception();
$e = new \Exception();
$trace = $e->getTrace();
$last_call = $trace[1]; //position 0 would be the line that called this function so we ignore it
//fwrite($this->myfile, print_r($last_call, TRUE));

        if ( ( strpos( strtolower($sql), 'insert') !== false ) or ( strpos( strtolower($sql), 'update') !== false ) )
        {
            $this->logger_err->error('*** Start *****************************************************************');
            $this->logger_err->error('** '.__METHOD__);
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error('PDO Error ('.$e->getMessage().')');
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error('QuerySQL -> SQL with insert or update' );
            $this->logger_err->error('*** End *****************************************************************');
            return false;
        }
        else
        {
             $this->logger->info('QuerySQL -> '.$sql );
        }

        try {
            $this->stmt = $this->dbConnectionRabbit->prepare($sql);
            $this->stmt->execute();
            return $this->stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
        catch (\PDOException $e)
        {
            //$pdo->rollBack() ;
            $this->logger_err->error('*** Start *****************************************************************');
            $this->logger_err->error('** '.__METHOD__);
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error('QuerySQL -> Error on querySQL:');
            $this->logger_err->error('PDO Error connection ('.$e->getMessage().')');
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error(print_r($last_call, TRUE));
            $this->logger_err->error('sql: '.$sql);
            $this->logger_err->error('*** End *******************************************************************');
            return false;
        }

    }
	
    /**
     * Fetches a table and returns one row
     *
     * @param $table
     * @param $fields
     * @param null $where
     * @param null $rest
     * @return mixed
     */
    public function fetchOneRabbit($table, $fields, $where = null, $rest = null)
    {
//$txt = 'db '.__FUNCTION__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);

        $this->fetch_itRabbit($table, $fields, $where, $rest);
        //$this->stmt->debugDumpParams();
        return $this->stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Fetches a table and returns multiple rows
     *
     * @param $table
     * @param $fields
     * @param null $where
     * @param null $rest
     * @return mixed
     */
    public function fetchAllRabbit($table, $fields, $where = null, $rest = null)
    {
//$txt = 'db '.__FUNCTION__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);

        $this->fetch_itRabbit($table, $fields, $where, $rest);
        return $this->stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function fetchFieldRabbit($table, $field, $where = null, $rest = null)
    {
//$txt = '-----> '.__FUNCTION__.' '.$table.' start field ('.$field.') where ('.print_r($where).') rest('.$rest.')'.PHP_EOL; fwrite($this->myfile, $txt);
//$e = new \Exception();
//$trace = $e->getTrace();
//$last_call = $trace[1]; //position 0 would be the line that called this function so we ignore it
//fwrite($this->myfile, print_r($last_call, TRUE));
        $this->fetch_itRabbit($table, $field, $where, $rest);
        $row = $this->stmt->fetchAll(\PDO::FETCH_ASSOC);
//$txt = '-----> '.__FUNCTION__.' end =>'.(( isset($row[0][$field]) )? '('.$row[0][$field].')' : '>>>>>>>>>>>>>>> Error <<<<<<<<<<<<<<<<').PHP_EOL; fwrite($this->myfile, $txt);
//fclose($myfile_fetch_field);
        return isset($row[0][$field])? $row[0][$field] : false;
    }

    /**
     * Fetches a table and returns the executed pdo object
     *
     * @param $table
     * @param $fields
     * @param null $where
     * @param null $rest
     * @return mixed
     */
    private function fetch_itRabbit($table, $fields, $where = null, $rest = null)
    {
//$txt = 'db '.__FUNCTION__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);

//$txt = '*************************************************************************************'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Table '.$table.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '*************************************************************************************'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '-----> fetch_itRabbit start table ('.$table.') fields ('.$fields.') where ('.print_r($where).') rest('.$rest.')'.PHP_EOL; fwrite($this->myfile, $txt);
$e = new \Exception();
$trace = $e->getTrace();
$last_call = $trace[1]; //position 0 would be the line that called this function so we ignore it
//fwrite($this->myfile, print_r($last_call, TRUE));
        //TODO-Carlos array of WHERE clausule with operation =, !=, >, <, ...
        // Just in case
        //$table = '`'.$table.'`';

        // Compose the FIELDS
        if ( $fields != '*')
        {
            $fields_temp = array();

            $fields_list = explode(', ', $fields);
            foreach ($fields_list as $field)
            {
                $fields_temp[] = '`' . $field . '`';
            }
            $fields = implode(', ', $fields_temp);
            unset($fields_temp);
        }
//$txt = 'fields '.$fields.PHP_EOL; fwrite($this->myfile, $txt);
        // Compose the WHERE clausule
        if ( $where )
        {
            $where_fields = array_keys($where);
            $where_values = array_values($where);

            $where_fields_tmp = array();
            for ($i = 0; $i < count($where_fields); $i++) {
                $where_fields_tmp[] = '`' . $where_fields[$i] . '`' . " = :" . $where_fields[$i];
            }
            $where_field_list=implode(' AND ', $where_fields_tmp);
            $where = " WHERE " . $where_field_list;
        }
//$txt = 'where '.$where.PHP_EOL; fwrite($this->myfile, $txt);
        $sql = "SELECT {$fields} FROM `{$table}` {$where} {$rest}";

        $this->stmt = $this->dbConnectionRabbit->prepare($sql);
//$txt = '('.$sql.')'.PHP_EOL; fwrite($this->myfile, $txt);

        // Bind parameters
        if ( $where )
        {
            for ($j = 0; $j < count($where_fields); $j++)
            {
                //echo $where_fields[$j] . " = :" . $where_values[$j];
                $this->bind(':'.$where_fields[$j], $where_values[$j]);
            }
        }

        if ( $_ENV['env_env'] == 'dev' or $_ENV['env_env'] == 'int' )
        {
            $this->logger->info('SQL -> '.$sql );
            if ( $where )
            {
                $txt = 'Binds => ';
                for ( $k = 0; $k < count($where_fields); $k++ )
                {
                    $txt .= $where_fields[$k] . ' -> ' . $where_values[$k] . ' | ';
                }
                $this->logger->info($txt);
            }
        }

        try
        {
            $result = $this->stmt->execute();
        }
        catch(\PDOException $e)
        {
            //$pdo->rollBack() ;
            $this->logger_err->error('*** Start *****************************************************************');
            $this->logger_err->error('** '.__METHOD__);
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error('PDO Error ('.$e->getMessage().')');
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error(print_r($last_call, TRUE));
            $this->logger_err->error('SQL: '.$sql);
            $this->logger_err->error('Binds: '.print_r($where_fields, TRUE) );
            $this->logger_err->error('*** End *******************************************************************');
            return false;
        }
        return $result;
    }

    /**
     * Inserts an asociative array into a table
     *
     * @param $table
     * @param $array
     * @return mixed
     */
    public function insertArrayRabbit($table, $array) {
//$txt = 'db '.__FUNCTION__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);

        $fields=array_keys($array);
        $values=array_values($array);

        // Adding backslash to fields name (to use reserved words as field names) and values
        for ($j = 0; $j < count($fields); $j++) {
            $fields[$j] = '`' . $fields[$j] . '`';
        }

        $fieldlist=implode(',', $fields);
        $qs=str_repeat("?,",count($fields)-1);

        $sql="INSERT INTO `".$table."` (".$fieldlist.") VALUES (${qs}?)";
//$txt = 'texto ('.$sql.')'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($array, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        try
        {
            $q = $this->dbConnectionRabbit->prepare($sql);
        }
        catch(\PDOException $e)
        {
            $this->logger_err->error('*** Start *****************************************************************');
            $this->logger_err->error('** '.__METHOD__);
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error('PDO Error connection ('.$e->getMessage().')');
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error('SQL '.$sql.')');
            $this->logger_err->error('*** End *****************************************************************');
            return false;
        }
        try
        {
            if ( $_ENV['env_env'] == 'dev' or $_ENV['env_env'] == 'int' )
            {
                $this->logger->info('SQL -> '.$sql );
                $txt = 'Binds => ';
                for ($k = 0; $k < count($fields); $k++)
                {
                    $txt .= $fields[$k].' -> '.$values[$k].' | ';
                }
                $this->logger->info($txt);
            }
            $q->execute($values);
        }
        catch(\PDOException $e)
        {
            $this->logger_err->error('*** Start *****************************************************************');
            $this->logger_err->error('** '.__METHOD__);
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error('PDO Error execute ('.$e->getMessage().')');
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error('SQL -> '.$sql.')');
            $this->logger_err->error('Field list -> '.serialize($array).')');
            $this->logger_err->error('*** End *****************************************************************');
            return false;
        }

        return $this->lastInsertId();
    }

    /**
     * Updates a table with an asociative array
     *
     * @param $table
     * @param $id_key_field
     * @param $id_key_value
     * @param $array
     * @return mixed
     */
    public function updateArrayRabbit($table, $id_key_field=NULL, $id_key_value=NULL, $array)
    {
//$txt = 'db '.__FUNCTION__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);

        $fields=array_keys($array);
        $values=array_values($array);
        //$fieldlist=implode(',', $fields);
        //$qs=str_repeat("?,",count($fields)-1);
        $firstfield = true;

        $sql = "UPDATE `".$table."` SET";
        for ($i = 0; $i < count($fields); $i++)
        {
            if(!$firstfield) {
                $sql .= ", ";
            }
            $sql .= ' `'.$fields[$i].'`'."=?";
            $firstfield = false;
        }
        if ( $id_key_field ) $sql .= ' WHERE `'.$id_key_field."` =?";

        //echo '['.$this->interpolateQuery($sql, $array).']<br />';

        $sth = $this->dbConnectionRabbit->prepare($sql);

        // We add the search needle to use the bind per array
        // obviously if a filter value is set
        if ( $id_key_value != '') $values[] = $id_key_value;

        try
        {
            if ( $_ENV['env_env'] == 'dev' or $_ENV['env_env'] == 'int' )
            {
                $this->logger->info('SQL -> '.$sql );
                $txt = 'Binds => ';
                for ($k = 0; $k < count($fields); $k++)
                {
                    $txt .= $fields[$k].' -> '.$values[$k].' | ';
                }
                $this->logger->info($txt);
            }
            $sth->execute($values);
        }
        catch(\PDOException $e)
        {
            $this->logger_err->error('*** Start *****************************************************************');
            $this->logger_err->error('** '.__METHOD__);
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error('PDO Error execute ('.$e->getMessage().')');
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error('SQL -> '.$sql.')');
            $this->logger_err->error('Field list -> '.serialize($array).')');
            $this->logger_err->error('*** End *****************************************************************');
            return false;
        }

        return true;
    }

    /**
     * Deletes records of a table
     *
     * @param $table
     * @param $id_key_field
     * @param $id_key_value
     */
    public function deleteRabbit($table, $id_key_field='', $id_key_value='')
    {
//$txt = 'db '.__FUNCTION__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
        $sql = 'DELETE FROM `' . $table . '`';
        if ( $id_key_field != '' )
        {
            $sql .= ' WHERE ' . '`' . $id_key_field . '`' . '=  :key';
        }
        $this->stmt = $this->dbConnectionRabbit->prepare($sql);
        if ( $id_key_field != '' ) $this->bind(':key', $id_key_value);
        try
        {
            if ( $_ENV['env_env'] == 'dev' or $_ENV['env_env'] == 'int' )
            {
                $this->logger->info('SQL -> '.$sql );
                $this->logger->info('Bind -> ' . $id_key_field . ' -> ' . $id_key_value);
            }
            $this->execute();
        }
        catch(\PDOException $e)
        {
            $this->logger_err->error('*** Start *****************************************************************');
            $this->logger_err->error('** '.__METHOD__);
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error('PDO Error deleting ('.$e->getMessage().')');
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error('SQL -> '.$sql.')');
            $this->logger_err->error('Bind -> ' . $id_key_field . ' -> ' . $id_key_value);
            $this->logger_err->error('*** End *****************************************************************');
            return false;
        }

        return true;
    }

    /**
     * Binds pdo parameters, the ones starting with :
     *
     * @param $param
     * @param $value
     * @param null $type
     */
    public function bind($param, $value, $type = null)
    {
//$txt = 'db '.__FUNCTION__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);

        if (is_null($type))
        {
            switch (true)
            {
                case is_int($value):
                    $type = \PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = \PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = \PDO::PARAM_NULL;
                    break;
                default:
                    $type = \PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    /**
     * Prepares a pdo query
     *
     * @param $query
     */
    public function queryRabbit($query)
    {
//$txt = 'db '.__FUNCTION__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
        $this->stmt = $this->dbConnectionRabbit->prepare($query);
    }

    /**
     * Executes a pdo prepared query
     *
     * @return mixed
     */
    public function execute()
    {
//$txt = 'db '.__FUNCTION__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
        return $this->stmt->execute();
    }

    /**
     * Executes a pdo query and returns the resulting associative array of rows
     *
     * @return mixed
     */
    public function resultset()
    {
//$txt = 'db '.__FUNCTION__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);

        $this->execute();
        return $this->stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Executes a pdo query and returns the resulting associative array of one row
     *
     * @return mixed
     */
    public function single()
    {
//$txt = 'db '.__FUNCTION__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);

        $this->execute();
        return $this->stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Returns the number of rows affected
     *
     * @return mixed
     */
    public function rowCount()
    {
//$txt = 'db '.__FUNCTION__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);

        return $this->stmt->rowCount();
    }

    /**
     * Returns the id of the last inserted record
     * @return mixed
     */
    public function lastInsertId()
    {
//$txt = 'db '.__FUNCTION__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);

        return $this->dbConnectionRabbit->lastInsertId();
    }

    /**
     * Starts a transaction
     *
     * @return mixed
     */
    public function beginTransaction()
    {
//$txt = 'db '.__FUNCTION__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
        return $this->dbConnectionRabbit->beginTransaction();
    }

    /**
     * Ends a transaction by commiting it.
     *
     * @return mixed
     */
    public function endTransaction()
    {
//$txt = 'db '.__FUNCTION__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
        return $this->dbConnectionRabbit->commit();
    }

    /**
     * Not sure
     * @return mixed
     */
    public function debugDumpParams()
    {
//$txt = 'db '.__FUNCTION__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);

        //TODO-Carlos Test what debugDumpParams() does
        return $this->stmt->debugDumpParams();
    }
}
