<?php
namespace src\util;

use src\util\debug_utils;

use DateTime;
use DateTimeZone;
use Exception;

class db
{
    protected $logger;
    protected $logger_err;

    protected $dbConnection;
    private $stmt;

    private $debug_utils;
    private $myfile;

    private $dbal_host;
    private $dbal_dbname;
    private $dbal_port;
    private $dbal_user;
    private $dbal_password;
    private $dbal_driver;
    private $dbal_charset;

    public function __construct( $dbal_host, $dbal_dbname, $dbal_port, $dbal_user, $dbal_password, $dbal_driver, $dbal_charset, $logger, $logger_err )
    {
        $this->dbal_host = $dbal_host;
        $this->dbal_dbname = $dbal_dbname;
        $this->dbal_port = $dbal_port;
        $this->dbal_user = $dbal_user;
        $this->dbal_password = $dbal_password;
        $this->dbal_driver = $dbal_driver;
        $this->dbal_charset = $dbal_charset;

        $this->logger = $logger;
        $this->logger_err = $logger_err;

        $this->debug_utils = new debug_utils();

//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/db_'.__FUNCTION__.'.txt', 'a+') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);

        $this->openDatabase();

//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     * Creates the pdo connection
     */
    private function openDatabase()
    {
//$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);

$last_call = $this->debug_utils->log_call_stack( __METHOD__ );
//fwrite($this->myfile, print_r($last_call, TRUE));

        // Set DSN
        $dsn = 'mysql:host='.$this->dbal_host.';port='.$this->dbal_port.';dbname='.$this->dbal_dbname.';charset='.$this->dbal_charset;
        // Set options
        $options = array(
            \PDO::ATTR_PERSISTENT    => true,
            \PDO::ATTR_ERRMODE       => \PDO::ERRMODE_EXCEPTION,    //Throw exceptions.
            //\PDO::ATTR_ERRMODE    => \PDO::ERRMODE_SILENT,     //Just set error codes.
            //\PDO::ATTR_ERRMODE    => \PDO::ERRMODE_WARNING,    //Raise E_WARNING.$options = [
            //\PDO::ATTR_ERRMODE    => \PDO::ERRMODE_EXCEPTION,
            //\PDO::ATTR_CASE       => \PDO::CASE_NATURAL,
            //\PDO::ATTR_ORACLE_NULLS => \PDO::NULL_TO_STRING,
            //\PDO::ATTR_EMULATE_PREPARES => false
            //];
        );
        // Create a new PDO instanace
        try
        {
            $this->dbConnection = new \PDO($dsn, $this->dbal_user, $this->dbal_password, $options);
        }
        catch(\PDOException $e)
        {
            $this->logger_err->error('*** Start *****************************************************************');
            $this->logger_err->error('** '.__METHOD__);
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error('Trace '.print_r($last_call, TRUE));
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error('PDO Error connection ('.$e->getMessage().')');
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error('Database: '.$dsn.' User: '.$this->dbal_user.'Pass: '.$this->dbal_password);
            $this->logger_err->error('*** End *****************************************************************');
        }
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     * Inteacts with table based on a sql sentece
     *
     * @param $sql
     *
     * @return array/bool   True if insert or update and Array in the other cases
     */
    public function querySQL( $sql )
    {
//$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
$last_call = $this->debug_utils->log_call_stack( __METHOD__ );
//fwrite($this->myfile, print_r($last_call, TRUE));

        if ( ( strpos( strtolower($sql), 'insert') !== false ) or ( strpos( strtolower($sql), 'update') !== false ) )
        {
            $this->logger_err->error('*** Start *****************************************************************');
            $this->logger_err->error('** '.__METHOD__);
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error('Trace '.print_r($last_call, TRUE));
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error('QuerySQL -> SQL with insert or update on querySQL' );
            $this->logger_err->error('*** End *****************************************************************');
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
            return false;
        }
        else
        {
            if ( $_ENV['env_log'] == 'true' ) $this->logger->info('QuerySQL -> '.$sql );
        }

        try {
            $this->stmt = $this->dbConnection->prepare($sql);
            $this->stmt->execute();
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
            return $this->stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
        catch (\PDOException $e)
        {
            //$pdo->rollBack() ;
            $this->logger_err->error('*** Start *****************************************************************');
            $this->logger_err->error('** '.__METHOD__);
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error('Trace '.print_r($last_call, TRUE));
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error('QuerySQL -> Error on querySQL:');
            $this->logger_err->error('PDO Error ('.$e->getMessage().')');
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error(print_r($last_call, TRUE));
            $this->logger_err->error('sql: '.$sql);
            $this->logger_err->error('*** End *******************************************************************');
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
            return false;
        }
    }

    /**
     * Interacts with table based on a sql sentece
     *
     * @param $sql
     * @return array/bool   True if insert or update and Array in the other cases
     */
    public function querySQL_ORL($table, $user, $sql)
    {
//$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
$last_call = $this->debug_utils->log_call_stack( __METHOD__ );
//fwrite($this->myfile, print_r($last_call, TRUE));

        if ( ( strpos( strtolower($sql), 'insert') !== false ) or ( strpos( strtolower($sql), 'update') !== false ) )
        {
            $this->logger_err->error('*** Start *****************************************************************');
            $this->logger_err->error('** '.__METHOD__);
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error('Trace '.print_r($last_call, TRUE));
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error('QuerySQL -> SQL with insert or update' );
            $this->logger_err->error('*** End *****************************************************************');
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
            return false;
        }

        // ORL log
        try
        {
            $time_zone = $this->fetchField('config', 'config_value', ['config_name' => 'time_zone']);
            $sql_orl="INSERT INTO `orl` SET createdby = :o_user, createddate = :createddate, entity = :o_table, new = :array_in";
            $this->stmt = $this->dbConnection->prepare($sql_orl);
            $user = ( $user == '' )? NULL : $user;
            $this->bind(':o_user', $user);
            $createddate = (new DateTime("now", new DateTimeZone($time_zone)))->format('Y-m-d H:i:s');
            $this->bind(':createddate', $createddate);
            $this->bind(':o_table', $table);
            $this->bind(':array_in', $sql);
            $this->stmt->execute();
        }
        catch(\PDOException $e) {
            $this->logger_err->error('*** Start *****************************************************************');
            $this->logger_err->error('** '.__METHOD__);
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error('Trace '.print_r($last_call, TRUE));
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error('QuerySQL -> Error on querySQL ORL');
            $this->logger_err->error('PDO Error ('.$e->getMessage().')');
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error(print_r($last_call, TRUE));
            $this->logger_err->error('sql: '.$sql);
            $this->logger_err->error('*** End *******************************************************************');
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
            return false;
        }

        try
        {
            $this->stmt = $this->dbConnection->prepare($sql);
            $this->stmt->execute();

            if ( $_ENV['env_log'] == 'true' ) $this->logger->info('USER '.$user.' | QuerySQL_ORL -> '.$sql );

            return $this->stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
        catch(\PDOException $e) {
            $this->logger_err->error('*** Start *****************************************************************');
            $this->logger_err->error('** '.__METHOD__);
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error('Trace '.print_r($last_call, TRUE));
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error('QuerySQL -> Error on querySQL');
            $this->logger_err->error('PDO Error ('.$e->getMessage().')');
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error(print_r($last_call, TRUE));
            $this->logger_err->error('sql: '.$sql);
            $this->logger_err->error('*** End *******************************************************************');
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
            return false;
        }
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
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
    public function fetchOne( $table, $fields, $where = null, $rest = null )
    {
//$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);

        $last_call = $this->debug_utils->log_call_stack( __METHOD__ );
//fwrite($this->myfile, print_r($last_call, TRUE));

        $this->fetch_it($table, $fields, $where, $rest);
        //$this->stmt->debugDumpParams();
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
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
    public function fetchAll( $table, $fields, $where = null, $rest = null )
    {
//$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
//$last_call = $this->debug_utils->log_call_stack( __METHOD__ );
//fwrite($this->myfile, print_r($last_call, TRUE));

        $this->fetch_it($table, $fields, $where, $rest);
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
        return $this->stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function fetchField($table, $field, $where = null, $rest = null)
    {
//$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '-----> Table '.$table.' start field ('.$field.') where ('.print_r($where).') rest('.$rest.')'.PHP_EOL; fwrite($this->myfile, $txt);
//$last_call = $this->debug_utils->log_call_stack( __METHOD__ );
//fwrite($this->myfile, print_r($last_call, TRUE));

        $this->fetch_it($table, $field, $where, $rest);
        $row = $this->stmt->fetchAll(\PDO::FETCH_ASSOC);
//$txt = '-----> '.(( isset($row[0][$field]) )? '('.$row[0][$field].')' : '>>>>>>>>>>>>>>> Error <<<<<<<<<<<<<<<<').PHP_EOL; fwrite($this-myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
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
    private function fetch_it($table, $fields, $where = null, $rest = null)
    {
//$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);

//$txt = '*************************************************************************************'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Table '.$table.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '*************************************************************************************'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '-----> fetch_it start table ('.$table.') fields ('.$fields.') where ('.print_r($where).') rest('.$rest.')'.PHP_EOL; fwrite($this->myfile, $txt);
        $tables_not_log = array ('config','lang_text','lang_text_name');
        if ( $_ENV['env_log'] == 'true' && !in_array ( $table, $tables_not_log ) )
        {
            $last_call = $this->debug_utils->log_call_stack(__METHOD__);
//fwrite($this->myfile, print_r($last_call, TRUE));
        }

        //TODO-Carlos array of WHERE clausule with operation =, !=, >, <, ...
        // Just in case
        //$table = '`'.$table.'`';

        $where_fields = array();

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

        $this->stmt = $this->dbConnection->prepare($sql);
//$txt = PHP_EOL.$sql.PHP_EOL; fwrite($this->myfile, $txt);

        // Bind parameters
        $params = array();
        if ( $where )
        {
            for ($j = 0; $j < count($where_fields); $j++)
            {
                //echo $where_fields[$j] . " = :" . $where_values[$j];
                $params[$where_fields[$j]] = $where_values[$j];
                $this->bind(':'.$where_fields[$j], $where_values[$j]);
            }
        }

        if ( $_ENV['env_log'] == 'true' && !in_array ( $table, $tables_not_log ) )
        {
            $this->logger->info('---------- Method -> '.print_r($last_call, TRUE) );
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
//$txt = '('.$sql.')'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Params: '.(isset($params))? print_r($params, TRUE): ''; fwrite($this->myfile, $txt);
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
            $this->logger_err->error('Binds: '.( isset( $where_fields ) && is_array( $where_fields ) )? print_r( $where_fields, TRUE): '' );
            $this->logger_err->error('Params: '.( isset( $params ) )? print_r( $params, TRUE): '' );
            $this->logger_err->error('*** End *******************************************************************');
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
            return false;
        }
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
        return $result;
    }

    /**
     * Inserts an asociative array into a table
     *
     * @param $table
     * @param $array
     * @return mixed
     */
    public function insertArray( $table, $array )
    {
//$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Table ('.$table.')'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($array, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
$last_call = $this->debug_utils->log_call_stack( __METHOD__ );
//fwrite($this->myfile, print_r($last_call, TRUE));

        $fields=array_keys($array);
        $values=array_values($array);

        // Adding backslash to fields name (to use reserved words as field names) and values
        for ($j = 0; $j < count($fields); $j++) {
            $fields[$j] = '`' . $fields[$j] . '`';
        }

        $fieldlist=implode(',', $fields);
        $qs=str_repeat("?,",count($fields)-1);

        $sql="INSERT INTO `".$table."` (".$fieldlist.") VALUES ({$qs}?)";
//$txt = 'SQL ('.$sql.')'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($array, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        try
        {
            $q = $this->dbConnection->prepare($sql);
        }
        catch(\PDOException $e)
        {
            $this->logger_err->error('*** Start *****************************************************************');
            $this->logger_err->error('** '.__METHOD__);
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error('Trace '.print_r($last_call, TRUE));
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error('PDO Error connection ('.$e->getMessage().')');
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error('SQL '.$sql.')');
            $this->logger_err->error('*** End *****************************************************************');
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
            return false;
        }
        try
        {
            if ( $_ENV['env_log'] == 'true' )
            {
                $this->logger->info('Method -> '.print_r($last_call, TRUE) );
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
            $this->logger_err->error('Trace '.print_r($last_call, TRUE));
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error('PDO Error execute ('.$e->getMessage().')');
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error('SQL -> '.$sql.')');
            $this->logger_err->error('Field list -> '.serialize($array).')');
            $this->logger_err->error('*** End *****************************************************************');
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
            return false;
        }
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
        return $this->lastInsertId();
    }

    /**
     * Inserts an asociative array into a table and then adds a record in the ORL
     *
     * @param $table
     * @param $user
     * @param $array
     * @return mixed
     */
    public function insertArrayORL($table, $user, $array)
    {
//$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
$last_call = $this->debug_utils->log_call_stack( __METHOD__ );
//fwrite($this->myfile, print_r($last_call, TRUE));

        $fields=array_keys($array);
        $values=array_values($array);

        // Adding backslash to fields name to use reserved words as field names
        for ($j = 0; $j < count($fields); $j++) {
            $fields[$j] = '`' . $fields[$j] . '`';
            //$values[$j] = '`' . $values[$j] . '`';
        }

        $fieldlist=implode(',', $fields);
        $qs=str_repeat("?,",count($fields)-1);

        $sql="INSERT INTO `".$table."` (".$fieldlist.") VALUES ({$qs}?)";

        $q = $this->dbConnection->prepare($sql);

        try
        {
            if ( $_ENV['env_log'] == 'true' )
            {
                $this->logger->info('Method -> '.print_r($last_call, TRUE) );
                $this->logger->info('USER '.$user.' | SQL -> '.$sql );
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
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
            return false;
        }

        $lastInserted = $this->lastInsertId();
        unset($values);

        // ORL log
        $time_zone = $this->fetchField('config', 'config_value', ['config_name' => 'time_zone']);
        $array['id'] = $this->lastInsertId();
        $sql="INSERT INTO `orl` SET createdby = :o_user, createddate = :createddate, entity = :o_table, new = :array_in";
        $this->stmt = $this->dbConnection->prepare($sql);
        $user = ( $user == '' )? NULL : $user;
        $this->bind(':o_user', $user);
        $createddate = (new DateTime("now", new DateTimeZone($time_zone)))->format('Y-m-d H:i:s');
        $this->bind(':createddate', $createddate);
        $this->bind(':o_table', $table);
        $this->bind(':array_in', json_encode($array));
        $this->stmt->execute();

//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
        return $lastInserted;
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
    public function updateArray($table, $id_key_field=NULL, $id_key_value=NULL, $array=NULL)
    {
//$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
$last_call = $this->debug_utils->log_call_stack( __METHOD__ );
//fwrite($this->myfile, print_r($last_call, TRUE));

        if ( $array === NULL )
        {
            $this->logger_err->error('*** Start *****************************************************************');
            $this->logger_err->error('** '.__METHOD__);
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error('Array to update is NULL.');
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error('Table -> '.$table.')');
            $this->logger_err->error('*** End *****************************************************************');
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
            return false;
        }

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

        $sth = $this->dbConnection->prepare($sql);

        // We add the search needle to use the bind per array
        // obviously if a filter value is set
        if ( $id_key_value != '') $values[] = $id_key_value;

        try
        {
            if ( $_ENV['env_log'] == 'true' )
            {
                $this->logger->info('Method -> '.print_r($last_call, TRUE) );
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
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
            return false;
        }
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
        return true;
    }

    /**
     * Adds a record in the ORL and then updates a table with an asociative array
     *
     * @param $table
     * @param $user
     * @param $id_key_field
     * @param $id_key_value
     * @param $array
     * @return mixed
     */
    public function updateArrayORL($table, $user, $id_key_field=NULL, $id_key_value=NULL, $array=NULL)
    {
//$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'SQL table '.$sql.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($array, TRUE));
$last_call = $this->debug_utils->log_call_stack( __METHOD__ );
//fwrite($this->myfile, print_r($last_call, TRUE));

        if ( empty($user) )
        {
            /*
            $this->logger_err->error('*** Start *****************************************************************');
            $this->logger_err->error('** '.__METHOD__);
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error('User is empty.');
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error('Table -> '.$table.')');
            $this->logger_err->error('*** End *****************************************************************');
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
            return false;
            */
        }
        if ( $array === NULL )
        {
            $this->logger_err->error('*** Start *****************************************************************');
            $this->logger_err->error('** '.__METHOD__);
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error('Array to update is NULL.');
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error('Table -> '.$table.')');
            $this->logger_err->error('*** End *****************************************************************');
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
            return false;
        }
        // ORL log
        $time_zone = $this->fetchField('config', 'config_value', ['config_name' => 'time_zone']);
        $where = array();
        $where[$id_key_field] = $id_key_value;
        $reg = $this->fetchOne( $table, '*', $where);
        $sql_ORL="INSERT INTO `orl` SET createdby = :o_user, createddate = :createddate, entity = :o_table, old = :array_out, new = :array_in";
        $this->stmt = $this->dbConnection->prepare($sql_ORL);
        $user = ( $user == '' )? NULL : $user;
        $this->bind(':o_user', $user);
        $createddate = (new DateTime("now", new DateTimeZone($time_zone)))->format('Y-m-d H:i:s');
        $this->bind(':createddate', $createddate);
        $this->bind(':o_table', $table);
        $this->bind(':array_out', json_encode($reg));
        $this->bind(':array_in', json_encode($array));
        $this->stmt->execute();

        $fields=array_keys($array);
        $values=array_values($array);
        //$fieldlist=implode(',', $fields);
        //$qs=str_repeat("?,",count($fields)-1);
        $firstfield = true;

        $sql = "UPDATE `".$table."` SET";
        for ($i = 0; $i < count($fields); $i++)
        {
            if(!$firstfield)
            {
                $sql .= ", ";
            }
            $sql .= " `".$fields[$i]."`=?";
            $firstfield = false;
        }
        if ( $id_key_field ) $sql .= ' WHERE `'.$id_key_field."` =?";

        // We add the search needle to use the bind per array
        // obviously if a filter value is set
        if ( $id_key_value != '') $values[] = $id_key_value;

        $sth = $this->dbConnection->prepare($sql);

        try
        {
            if ( $_ENV['env_log'] == 'true' )
            {
                $this->logger->info('Method -> '.print_r($last_call, TRUE) );
                $this->logger->info('USER '.$user.' | SQL -> '.$sql );
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
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
            return false;
        }
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
        return true;
    }

    /**
     * Deletes records of a table
     *
     * @param $table
     * @param $id_key_field
     * @param $id_key_value
     */
    public function delete($table, $id_key_field='', $id_key_value='')
    {
//$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
//$last_call = $this->debug_utils->log_call_stack( __METHOD__ );
//fwrite($this->myfile, print_r($last_call, TRUE));

        $sql = 'DELETE FROM `' . $table . '`';
        if ( $id_key_field != '' )
        {
            $sql .= ' WHERE ' . '`' . $id_key_field . '`' . '=  :key';
        }
        $this->stmt = $this->dbConnection->prepare($sql);
        if ( $id_key_field != '' ) $this->bind(':key', $id_key_value);
        $param = ':key'.' '.$id_key_value;
        try
        {
            if ( $_ENV['env_log'] == 'true' )
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
            $this->logger_err->error('Params: '.(isset($param))? $param : '');
            $this->logger_err->error('*** End *****************************************************************');
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
            return false;
        }
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
        return true;
    }

    /**
     * Adds a record in the ORL and then deletes one record of a table
     *
     * @param string $table Table name
     * @param int $user User id
     * @param string $id_key_field Field to be used as a key to find the record to be deleted
     * @param string $id_key_value Value of the field to be used as a key to find the record to be deleted
     * @return boolean Success of the deletion
     */
    public function deleteORL( $table, $user, $id_key_field, $id_key_value )
    {
//$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
//$last_call = $this->debug_utils->log_call_stack( __METHOD__ );
//fwrite($this->myfile, print_r($last_call, TRUE));

        // ORL log
        $time_zone = $this->fetchField('config', 'config_value', ['config_name' => 'time_zone']);
        $row = $this->fetchOne( $table, '*', [$id_key_field => $id_key_value]);
        $sql="INSERT INTO `orl` SET createdby = :o_user, createddate = :createddate, entity = :o_table, old = :array_out";
        $this->stmt = $this->dbConnection->prepare($sql);
        $user = ( $user == '' )? NULL : $user;
        $this->bind(':o_user', $user);
        $createddate = (new DateTime("now", new DateTimeZone($time_zone)))->format('Y-m-d H:i:s');
        $this->bind(':createddate', $createddate);
        $this->bind(':o_table', $table);
        $this->bind(':array_out', json_encode($row));
        $this->stmt->execute();

        $sql = 'DELETE FROM `' . $table . '` WHERE ' . '`' . $id_key_field . '`' . '=  :key';
        $this->stmt = $this->dbConnection->prepare($sql);
        $this->bind(':key', $id_key_value);

        try
        {
            if ( $_ENV['env_log'] == 'true' )
            {
                $this->logger->info('User '.$user.' | SQL -> '.$sql );
                $this->logger->info('Bind -> ' . $id_key_field . ' -> ' . $id_key_value);
            }
            $this->execute();
        }
        catch(\PDOException $e)
        {
            $this->logger_err->error('*** Start *****************************************************************');
            $this->logger_err->error('** '.__METHOD__);
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error('PDO Error execute ('.$e->getMessage().')');
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error('SQL -> '.$sql.')');
            $this->logger_err->error('Bind -> ' . $id_key_field . ' -> ' . $id_key_value);
            $this->logger_err->error('*** End *****************************************************************');
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
            return false;
        }
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
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
//$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);

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
//$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     * Prepares a pdo query
     *
     * @param $query
     */
    public function query($query)
    {
//$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
        $this->stmt = $this->dbConnection->prepare($query);
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     * Executes a pdo prepared query
     *
     * @return mixed
     */
    public function execute()
    {
//$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
        return $this->stmt->execute();
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     * Executes a pdo query and returns the resulting associative array of rows
     *
     * @return mixed
     */
    public function resultset()
    {
//$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
        $this->execute();
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
        return $this->stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Executes a pdo query and returns the resulting associative array of one row
     *
     * @return mixed
     */
    public function single()
    {
//$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
        $this->execute();
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
        return $this->stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Returns the number of rows affected
     *
     * @return mixed
     */
    public function rowCount()
    {
//$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);

        return $this->stmt->rowCount();
    }

    /**
     * Returns the id of the last inserted record
     * @return mixed
     */
    public function lastInsertId()
    {
//$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
        return $this->dbConnection->lastInsertId();
    }

    /**
     * Starts a transaction
     *
     * @return mixed
     */
    public function beginTransaction()
    {
//$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
        return $this->dbConnection->beginTransaction();
    }

    /**
     * Ends a transaction by commiting it.
     *
     * @return mixed
     */
    public function endTransaction()
    {
//$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
        return $this->dbConnection->commit();
    }

    /**
     * Not sure
     * @return mixed
     */
    public function debugDumpParams()
    {
//$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
        //TODO-Carlos Test what debugDumpParams() does
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
        return $this->stmt->debugDumpParams();
    }
    /**
     * Replaces any parameter placeholders in a query with the value of that
     * parameter. Useful for debugging. Assumes anonymous parameters from
     * $params are are in the same order as specified in $query
     *
     * @param string $query The sql query with parameter placeholders
     * @param array $params The array of substitution parameters
     * @return string The interpolated query
     */
    /*
    public function interpolateQuery($query, $params)
    {
        $keys = array();
        $values = $params;

        # build a regular expression for each parameter
        foreach ($params as $key => $value)
        {
            if (is_string($key))
            {
                $keys[] = '/:'.$key.'/';
            }
            else
            {
                $keys[] = '/[?]/';
            }

            if (is_string($value))
                $values[$key] = "'" . $value . "'";

            if (is_array($value))
                $values[$key] = "'" . implode("','", $value) . "'";

            if (is_null($value))
                $values[$key] = 'NULL';
        }

        $query = preg_replace($keys, $values, $query, 1, $count);

        return $query;
    }
    */
    /*
    * Examples
    */
    /*
    Select a single row
    ===================

    $this->pdo->query('SELECT FName, LName, Age, Gender FROM mytable WHERE FName = :fname');
    $this->pdo->bind(':fname', 'Jenny');
    $row = $this->pdo->single();
    echo "<pre>";print_r($row);echo "</pre>";

    or

    $this->pdo->fetchOne('users', 'id, username', ['username' => $reg['username'], 'email' => $reg['email']]);

    Select multiple rows
    ====================

    $this->pdo->query('SELECT FName, LName, Age, Gender FROM mytable WHERE LName = :lname');
    $this->pdo->bind(':lname', 'Smith');
    $rows = $this->pdo->resultset();
    echo "<pre>";print_r($rows);echo "</pre>";
    echo $this->pdo->rowCount();

    or

    $this->pdo->fetchAll('users', '*', ['username' => $reg['username'], 'email' => $reg['email']]);

    Update a record
    ===================

    $this->pdo->query('UPDATE mytable (FName, LName, Age, Gender) VALUES (:fname, :lname, :age, :gender) WHERE user_id = :user_id');
    $this->pdo->bind(':fname', 'John');
    $this->pdo->bind(':lname', 'Smith');
    $this->pdo->bind(':age', '24');
    $this->pdo->bind(':gender', 'male');
    $this->pdo->bind(':user_id', '33');
    $this->pdo->execute();

    or

    $this->pdo->updateArray('users', 'id', $user_id, ['activation_key' => $random, 'is_active' => false]);

    Insert a new record
    ===================

    $this->pdo->query('INSERT INTO mytable (FName, LName, Age, Gender) VALUES (:fname, :lname, :age, :gender)');
    $this->pdo->bind(':fname', 'John');
    $this->pdo->bind(':lname', 'Smith');
    $this->pdo->bind(':age', '24');
    $this->pdo->bind(':gender', 'male');
    $this->pdo->execute();
    user_id = $this->pdo->lastInsertId();

    or

    $this->pdo->insertArray('users', $reg);
    $user_id = $this->pdo->lastInsertId();

    Insert multiple records using a Transaction
    ===========================================

    $this->pdo->beginTransaction();
    // Set the query.
    $this->pdo->query('INSERT INTO mytable (FName, LName, Age, Gender) VALUES (:fname, :lname, :age, :gender)');
    // Bind data to the placeholders.
    $this->pdo->bind(':fname', 'Jenny');
    $this->pdo->bind(':lname', 'Smith');
    $this->pdo->bind(':age', '23');
    $this->pdo->bind(':gender', 'female');
    // Execute the statement.
    $this->pdo->execute();
    // Bind the second set of data.
    $this->pdo->bind(':fname', 'Jilly');
    $this->pdo->bind(':lname', 'Smith');
    $this->pdo->bind(':age', '25');
    $this->pdo->bind(':gender', 'female');
    // Run the execute method again.
    $this->pdo->execute();
    // Echo out lastInsertId again.
    echo $this->pdo->lastInsertId();
    // End the transaction
    $this->pdo->endTransaction();

    */
}
