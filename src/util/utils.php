<?php
namespace src\util;

use Doctrine\DBAL\Schema\Table;

include APP_ROOT_PATH.'/src/util/utils/api.php';
include APP_ROOT_PATH.'/src/util/utils/config.php';
include APP_ROOT_PATH.'/src/util/utils/dates.php';
include APP_ROOT_PATH.'/src/util/utils/form_token.php';
include APP_ROOT_PATH.'/src/util/utils/lang.php';
include APP_ROOT_PATH.'/src/util/utils/lang_text.php';
include APP_ROOT_PATH.'/src/util/utils/location.php';

include APP_ROOT_PATH.'/src/util/utils/payment_type.php';

include APP_ROOT_PATH.'/src/util/utils/website.php';

include APP_ROOT_PATH.'/src/util/utils/blog.php';

include APP_ROOT_PATH.'/src/util/utils/images.php';

include APP_ROOT_PATH.'/src/util/utils/account.php';
include APP_ROOT_PATH.'/src/util/utils/account_payment_method.php';
include APP_ROOT_PATH.'/src/util/utils/group.php';
include APP_ROOT_PATH.'/src/util/utils/user.php';
include APP_ROOT_PATH.'/src/util/utils/category.php';
include APP_ROOT_PATH .'/src/util/utils/rag.php';
include APP_ROOT_PATH.'/src/util/utils/product.php';
include APP_ROOT_PATH.'/src/util/utils/coupon.php';
include APP_ROOT_PATH.'/src/util/utils/stripe.php';

include APP_ROOT_PATH.'/src/util/utils/quote.php';

include APP_ROOT_PATH.'/src/util/utils/entity.php';
include APP_ROOT_PATH.'/src/util/utils/entity_contact.php';

include APP_ROOT_PATH.'/src/util/utils/external_api.php';
include APP_ROOT_PATH.'/src/util/utils/domain.php';

include APP_ROOT_PATH.'/src/util/utils/print_q.php';

include APP_ROOT_PATH.'/src/util/utils/rabbit_db.php';

include APP_ROOT_PATH.'/src/util/utils/credential_type.php';

/**
 * Class utils
 * @package Utils
 *
 * @var \PDO $db    PDO Object
 * @var \Logger $logger    Log Object
 */
class utils
{
    protected $db;
    protected $logger;
    protected $logger_err;

    private $myfile;
    
    // Traits
    use \src\util\utils\api;
    use \src\util\utils\config;
    use \src\util\utils\dates;
    use \src\util\utils\form_token;
    use \src\util\utils\lang;
    use \src\util\utils\lang_text;
    use \src\util\utils\location;

    use \src\util\utils\payment_type;

    use \src\util\utils\website;

    use \src\util\utils\blog;

    use \src\util\utils\images;

    use \src\util\utils\account;
    use \src\util\utils\account_payment_method;
    use \src\util\utils\user;
    use \src\util\utils\group;
    use \src\util\utils\category;
    use \src\util\utils\rag;
    use \src\util\utils\product;
    use \src\util\utils\coupon;
    use \src\util\utils\stripe;

    use \src\util\utils\quote;

    use \src\util\utils\entity;
    use \src\util\utils\entity_contact;

    use \src\util\utils\external_api;
    use \src\util\utils\domain;

    use \src\util\utils\print_q;

    use \src\util\utils\rabbit_db;

    use \src\util\utils\credential_type;

//this->$myfile = fopen(APP_ROOT_PATH.'/var/logs/utils_'.__FUNCTION__'.txt', 'a+') or die('Unable to open file!');
//$txt = 'utils request_pagination start ==============================================================='.PHP_EOL;
//fwrite($this->myfile, $txt);
//$txt = 'POST ========>'.PHP_EOL;
//fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_POST['pagination'], TRUE));
//$txt = 'SESSION ========>'.PHP_EOL;
//fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_SESSION['alert']['pagination'], TRUE));
//$txt = 'Pagination ========>'.PHP_EOL;
//fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($pagination, TRUE));
//$txt = 'utilsController request_pagination end ==============================================================='.PHP_EOL;
//fwrite($this->myfile, $txt);
//fclose($this->myfile);

    /**
     * utils constructor.
     *
     * @param  \PDO $db    PDO Object
     * @param  \Monolog\logger $logger    Logger Object
     *
     */
    public function __construct( $db, $logger, $logger_err )
    {
        $this->db = $db;
        $this->logger = $logger;
        $this->logger_err = $logger_err;

//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/utils_'.__FUNCTION__.'.txt', 'a+') or die('Unable to open file!');
//$txt = 'utils '.__FUNCTION__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);

    }
    // TODO-Carlos: Divide this class in subfiles, perhaps Dependency Injection
    // Must to see https://stackoverflow.com/questions/16597358/how-to-split-a-php-class-to-separate-files


    /**
     * Do a URL redirect
     *
     * @param string $url   URL to be redirected
     * @param int $status   HTTP status send (Not used)
    */
    public function redirect( $url, $status = 302 )
    {
//        $txt = 'utils '.__FUNCTION__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);

        //header('Location: ', true, $status);
        header('Location: '.$_ENV['protocol'].'://' . $_ENV['domain'] . $url);
        exit;
    }

    /**
     * Get the current page path. Eg: /mypage, /folder/mypage.php
     *
     * @return mixed    Anything between / and ? on the url
    */
    public function curPage()
    {
//$txt = 'utils '.__FUNCTION__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
        $parts = parse_url( $this->curPageURL() );
//$txt = 'Cur Page ==> '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($parts, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        $path = ( isset($parts['path']) )? $parts['path'] : '';
//$txt = 'utils '.__FUNCTION__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
        return $path;
    }

    /**
    * Get the current page URL
     *
     * @return string   Composed URL based on $_SERVER
    */
    public function curPageURL()
    {
//$txt = 'utils '.__FUNCTION__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $pageURL = 'http';
        if( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ) $pageURL .= 's';
        $pageURL .= '://';
        // This is old, port 443 not well resolved
        /*
        if($_SERVER['SERVER_PORT'] != '80')
        {
            $pageURL .= $_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI'];
        }
        else
        {
            $pageURL .= $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
        }
        */
        $pageURL .= $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
//$txt = 'Cur Page URL ==> '.$pageURL.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        return $pageURL;
    }

    /**
    * Friendfy urls
     *
     * @param string $url          URL to friendfy
     * @return mixed|string URL friendfied
    */
    public function friendfy( $url )
    {
//$txt = 'utils '.__FUNCTION__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);

        $url = strtolower($url);

        // Adding hyphens
        $find = array(' ', '&', '\r\n', '\n', '+', '.');
        $url = str_replace ($find, '-', $url);

        // Char substitution
        $find = array('á', 'é', 'í', 'ó', 'ú', 'ñ');
        $repl = array('a', 'e', 'i', 'o', 'u', 'n');
        $url = str_replace ($find, $repl, $url);

        // Delete and remplace other special chars
        $find = array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/');
        $repl = array('', '-', '');
        $url = preg_replace ($find, $repl, $url);

        return $url;
    }

    public function del_dir( $dir )
    {
//$txt = 'utils '.__FUNCTION__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);

        if ( $dir == '' ) return false;

        $files = array_diff(scandir($dir), array('.', '..'));

        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
        }

        return rmdir($dir);
    }

    //**************************************************************************************************************
    //   ARRAYS
    //**************************************************************************************************************

    /**
     * Merges array 2 into array 1 identical keys
     *
     * @param   array $arr_1 Array to be changed.
     * @param   array $arr_2 Array with changes.
     * @return  array
     */
    public function array_merge($arr_1, $arr_2)
    {
//$txt = 'utils '.__FUNCTION__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);

        assert( is_array($arr_1));
        assert( is_array($arr_2));
//$txt = 'Arr 1 ========>' . PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($arr_1, TRUE));
//$txt = 'Arr 2 ========>' . PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($arr_2, TRUE));
        foreach ( $arr_2 as $key => $value )
        {
//$txt = 'Arr 2 Key =>'.$key. PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
            if ( isset ( $arr_1[$key] ) )
            {
//$txt = 'Arr 1 Key exists =>'.$value. PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
                $arr_1[$key] = $value;
            }
        }
//$txt = 'Arr result ========>' . PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($arr_1, TRUE));
        return $arr_1;
    }

    /**
     * Moves an array element before a given key
     *
     * @param array $array  Original array to be reordered
     * @param string $find  Element to be preceded by the element in $move
     * @param string $move  Element key to move
     * @return array        Array with moved element, if $find or before not array keys returns unalterated array
     */
    function moveKeyBefore($arr, $find, $move) {
//$txt = 'utils '.__FUNCTION__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);

        if (!isset($arr[$find], $arr[$move])) {
            return $arr;
        }

        $elem = [$move=>$arr[$move]];  // cache the element to be moved
        $start = array_splice($arr, 0, array_search($find, array_keys($arr)));
        unset($start[$move]);  // only important if $move is in $start
        return $start + $elem + $arr;
    }

    //**************************************************************************************************************
    //   FILES and FOLDERS
    //**************************************************************************************************************

    /**
     * Checks if a folder exist and is a folder
     *
     * @param   string $folder the path being checked.
     * @return  boolean
     */
    public function folder_exist($folder)
    {
//$txt = 'utils '.__FUNCTION__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);

        // Get canonicalized absolute pathname
        //$path = realpath($folder);

        // If it exist, check if it's a directory
        if( is_dir( $folder ) )
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    //**************************************************************************************************************
    //   UTILS
    //**************************************************************************************************************/**

    /**
    * Gets vars from request
    *
     * @param string $var_name             Var name
     * @param string $default              Var default value
     * @param string $origin        Where to search the var: ALL, COOKIE, POST, GET, POSTANDGET
     * @param bool|false $multibyte Is multibyte
     * @return array|mixed|string   Var value
    */
    public function request_var($var_name, $default, $origin='ALL', $multibyte = true)
    {
//$txt = 'utils '.__FUNCTION__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Args ==========> varname ('.$var_name.') default ('.$default.') origin ('.$origin.') multibyte ('.$multibyte.')'.PHP_EOL; fwrite($this->myfile, $txt);
        // Origin values:
        if ( $origin == 'ALL' ) {
//$txt = 'Origin ALL'.PHP_EOL; fwrite($this->myfile, $txt);
            if ( isset($_COOKIE[$var_name] ) ){
                $result = $_COOKIE[$var_name];
            }elseif ( isset($_POST[$var_name]) ) {
                $result = $_POST[$var_name];
            }elseif ( isset($_GET[$var_name]) ) {
                $result = $_GET[$var_name];
            }else {
                $result = $default;
            }
        }elseif ( $origin == 'POSTANDGET' ){
//$txt = 'Origin POSTANDGET'.PHP_EOL; fwrite($this->myfile, $txt);
            $result = isset($_POST[$var_name]) ? $_POST[$var_name] : ( isset($_GET[$var_name]) ? $_GET[$var_name] : $default);
        }else{
            switch ($origin) {
                case 'COOKIE':
//$txt = 'Origin COOKIE'.PHP_EOL; fwrite($this->myfile, $txt);
                    $result = isset($_COOKIE[$var_name]) ? $_COOKIE[$var_name] : $default;
                    break;
                case 'POST':
//$txt = 'Origin POST'.PHP_EOL; fwrite($this->myfile, $txt);
                    $result = isset($_POST[$var_name]) ? $_POST[$var_name] : $default;
                    break;
                case 'GET':
//$txt = 'Origin GET'.PHP_EOL; fwrite($this->myfile, $txt);
                    $result = isset($_GET[$var_name]) ? $_GET[$var_name] : $default;
                    break;
            }
        }
        if ( !is_array($result) ) {
            if (!empty($result)) {
//$txt = 'Var ==========> '.$var_name.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '    Original => '.$result.PHP_EOL; fwrite($this->myfile, $txt);
                $result = str_replace(array('\r\n', '\r', '\0'), array('\n', '\n', ''), $result);
//$txt = '    Replace===> '.$result.PHP_EOL; fwrite($this->myfile, $txt);
                $result = htmlspecialchars($result, ENT_COMPAT, 'UTF-8');
//$txt = '    Special===> '.$result.PHP_EOL; fwrite($this->myfile, $txt);
                $result = trim($result);
//$txt = '    Trim======> '.$result.PHP_EOL; fwrite($this->myfile, $txt);

                // Make sure multibyte characters are well formed
                if ( $multibyte ) {
                    if (!preg_match('/^./u', $result)) {
                        $result = '';
                    }
                } else {
                    // no multibyte, allow only ASCII (0-127)
                    $result = preg_replace('/[\x80-\xFF]/', '?', $result);
                }
                // Sanitize the result
                //TODO-Carlos: Sanitize input vars with filter_var ( mixed $variable [, int $filter = FILTER_DEFAULT [, mixed $options ]] ) : mixed
                //https://www.php.net/manual/en/function.filter-var.php
                //https://www.php.net/manual/en/filter.filters.sanitize.php
            }
        }
        
//$txt = '    Return====> '.$result.PHP_EOL; fwrite($this->myfile, $txt);

        return (is_array($result))? $result : stripslashes($result);
    }
    /**
    *
     * Gets vars from array in request* Origin values: ALL, COOKIE, POST, GET, POSTANDGET
    *
     * @param array $array                Array name
     * @param string $var_name             Var name
     * @param string $default              Var default value
     * @param string $origin        Where to search the array: ALL, COOKIE, POST, GET, POSTANDGET
     * @param bool|false $multibyte Is multibyte
     * @return array|mixed|string   Var value
     */
    
    public function request_var_array($array, $var_name, $default, $origin='ALL', $multibyte = false)
    {
//$txt = 'utils '.__FUNCTION__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);

//$txt = 'Args ==========> varname ('.$var_name.') default ('.$default.') origin ('.$origin.') multibyte ('.$multibyte.')'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'POST ========>'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_POST['pagination'], TRUE));
//$txt = 'SESSION ========>'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_SESSION['alert']['pagination'], TRUE));
//$txt = 'Pagination ========>'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($pagination, TRUE));
        // Origin values:
        if ( $origin == 'ALL' ) {
            if ( isset($_COOKIE[$array][$var_name] ) ){
                $result = $_COOKIE[$array][$var_name];
            }elseif ( isset($_POST[$array][$var_name]) ) {
                $result = $_POST[$array][$var_name];
            }elseif ( isset($_GET[$array][$var_name]) ) {
                $result = $_GET[$array][$var_name];
            }else {
                $result = $default;
            }
        }elseif ( $origin == 'POSTANDGET' ){
            $result = isset($_POST[$array][$var_name]) ? $_POST[$array][$var_name] : ( isset($_GET[$array][$var_name]) ? $_GET[$array][$var_name] : $default);
        }else{
            switch ($origin) {
                case 'COOKIE':
                    $result = isset($_COOKIE[$array][$var_name]) ? $_COOKIE[$array][$var_name] : $default;
                    break;
                case 'POST':
                    $result = isset($_POST[$array][$var_name]) ? $_POST[$array][$var_name] : $default;
                    break;
                case 'GET':
                    $result = isset($_GET[$array][$var_name]) ? $_GET[$array][$var_name] : $default;
                    break;
            }
        }
       if ( !is_array($result) ) {
            if (!empty($result)) {
                $result = trim(htmlspecialchars(str_replace(array('\r\n', '\r', '\0'), array('\n', '\n', ''), $result), ENT_COMPAT, 'UTF-8'));

                // Make sure multibyte characters are wellformed
                if ($multibyte) {
                    if (!preg_match('/^./u', $result)) {
                        $result = '';
                    }
                } else {
                    // no multibyte, allow only ASCII (0-127)
                    $result = preg_replace('/[\x80-\xFF]/', '?', $result);
                }
            }
        }

        return (is_array($result))? $result : stripslashes($result);
    }

    /**
     * Gets lists vars for pagination
     *
     * @param $pagination
     * @return array
    */
    public function request_pagination( $pagination )
    {
//$txt = 'utils '.__FUNCTION__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);

//$txt = 'POST utils entrada'.PHP_EOL; fwrite($this->myfile, $txt);  
//fwrite($this->myfile, print_r($pagination, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        if ( isset($_POST['pagination']) )
        {
//$txt = 'Reemplazado con POST'.PHP_EOL; fwrite($this->myfile, $txt);  
//fwrite($this->myfile, print_r($_POST['pagination'], TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

            $pagination = array_replace( $pagination, $_POST['pagination']);
        }
        elseif ( isset($_SESSION['alert']['pagination']) )
        {
//$txt = 'Reemplazado con session'.PHP_EOL; fwrite($this->myfile, $txt);  
//fwrite($this->myfile, print_r($_SESSION['alert']['pagination'], TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $pagination = $_SESSION['alert']['pagination'];
        }

//$txt = 'POST utils salida'.PHP_EOL; fwrite($this->myfile, $txt);  
//fwrite($this->myfile, print_r($pagination, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        return $pagination;
    }

    /**
     * Gets lists filters vars
     *
     * @param $filters
     * @param $num_page
     * @return array
     */
    public function request_filters( $filters, $num_page )
    {
//$txt = 'utils '.__FUNCTION__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Num page ini '.$num_page.PHP_EOL; fwrite($this->myfile, $txt);
        if ( isset($_POST['filters']) )
        {
            //foreach( $_POST['filters'] as $key => $value )
            foreach( array_keys($_POST['filters']) as $key )

            {
//$txt = 'key '.$key.' previous ('.$_POST['filters'][$key]['value_previous'].') actual ('.$_POST['filters'][$key]['value'].')'.PHP_EOL; fwrite($this->myfile, $txt);

//$txt = '('.$key.')'.PHP_EOL; fwrite($this->myfile, $txt);  
//fwrite($this->myfile, print_r($value, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);


                if ( $_POST['filters'][$key]['value_previous'] != $_POST['filters'][$key]['value'] ) $num_page = '1';
                $_POST['filters'][$key]['value_previous'] = $_POST['filters'][$key]['value'];
            }

            $filters = array_replace( $filters, $_POST['filters']);
        }
        elseif ( isset($_SESSION['alert']['filters']) )
        {
            // If filters comming from SESSION is because is an error/issue handle so back to the same place
            $filters = array_replace( $filters, $_SESSION['alert']['filters']);
        }

//$txt = 'Num page end '.$num_page.PHP_EOL; fwrite($this->myfile, $txt);
        return array($filters, $num_page);
    }

    /**
     * Find table records list paginated
     *
     * @param string $custom_sql Sql
     * @param array $pagination   Pagination details num_page, rpp, order, order_dir
     * @return array        Records accomplish pagination and filters
     */
    public function getResultAndCountSQL( $custom_sql, $pagination )
    {
//$txt = 'utils '.__FUNCTION__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Custom sql '.PHP_EOL.$custom_sql.PHP_EOL; fwrite($this->myfile, $txt);

        if ($pagination['num_page'] == '') $pagination['num_page'] = '1';

        // Counting records
        $this->db->query( $custom_sql );
        $rows_temp = $this->db->resultset();
        $count = $this->db->rowCount();
        unset($rows_temp);

        $custom_sql .= ' ORDER BY ' . $pagination['order'] . ' ' . $pagination['order_dir'] . '';

        $start = ($pagination['num_page'] - 1) * $pagination['rpp'];
        $custom_sql .= ' LIMIT ' . $start . ', ' . $pagination['rpp'] . '';
//$txt = 'Final sql '.$custom_sql.PHP_EOL; fwrite($this->myfile, $txt);
        $this->db->query($custom_sql);
        $rows = $this->db->resultset();
        return array($rows, $count);
    }
    /**
     * Find table records list paginated
     *
     * @param Table $table        Table to recup the records
     * @param array $pagination   Pagination details num_page, rpp, order, order_dir
     * @param array $list_filters Array with table fields and value to filter the result
     * @return array        Records accomplish pagination and filters
     */
    public function getResultAndCount( $table, $pagination, $list_filters, $custom_sql=false )
    {
//$txt = 'utils '.__FUNCTION__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($list_filters, TRUE));
//$txt = 'Table '.$table.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Custom sql '.(( $custom_sql )? $custom_sql : 'No').PHP_EOL; fwrite($this->myfile, $txt);
        if ($pagination['num_page'] == '') $pagination['num_page'] = '1';
        // Sometimes the rpp is lost by old $_SESSION
        //if ( $pagination['rpp'] == '' ) $pagination['rpp'] = $rpp;

        // Counting records
        $sql = ( $custom_sql )? $custom_sql : 'SELECT * FROM `' . $table . '`';

        $where_filters = array();
//fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        foreach ($list_filters as $filter_name => $filter_data) {
//$txt = 'Filter =======> '.$filter_name.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($filter_data, TRUE));
            if ($filter_data['type'] == 'text') {
                if ($filter_data['value'] !== '') {
                    $where_filters[$filter_name] = '`' . $filter_name . '`' . " LIKE CONCAT('%', :" . $filter_name . ", '%')";
                }
            } elseif ($filter_data['type'] == 'select') {
                if ($filter_data['value'] !== '' && $filter_data['value'] != '0' && $filter_data['value'] != '-') {
                    $where_filters[$filter_name] = '`' . $filter_name . '`' . ' = :' . $filter_name . '';
                }
            } elseif ($filter_data['type'] == 'hidden') {
                if ($filter_data['value'] !== '0' && $filter_data['value'] !== '-') {
                    $where_filters[$filter_name] = '`' . $filter_name . '`' . ' = :' . $filter_name . '';
                }
            }
            /*
            elseif ( $filter_data['type'] == 'array' )
            {
                if ( sizeof($filter_data['value']) )
                {
                    //echo '<br />('.$filter_data['value'].') '.gettype($filter_data['value']);
                    $filter_data['value'] = explode(', ', $filter_data['value']);
                    //echo '<br />('.print_r($filter_data['value']).') '.gettype($filter_data['value']);
                    $where_filters[$filter_name] = '(';
                    foreach( $filter_data['value'] as $filter_data_value)
                    {
                        $where_filters[$filter_name] .= $filter_name .' = ' . $filter_data_value . ' OR ';
                    }
                    $where_filters[$filter_name] = rtrim($where_filters[$filter_name], 'OR ');
                    $where_filters[$filter_name] .= ')';
                }
            }
            */
        }
        if (sizeof($where_filters))
        {
            if ( !$custom_sql ) $sql .= ' WHERE ';
            $sql .= implode(' AND ', $where_filters);
        }
//$txt = 'Count records sql ('.$sql.')'.PHP_EOL; fwrite($this->myfile, $txt);
        $this->db->query( $sql );
        if ( sizeof( $where_filters ) )
        {
//fwrite($this->myfile, print_r($list_filters, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
            foreach( $where_filters as $filter_name => $filter_data )
            {
                $this->db->bind(':'.$filter_name, $list_filters[$filter_name]['value']);
            }
        }
        $rows_temp = $this->db->resultset();
        $count = $this->db->rowCount();
        unset($rows_temp);

        $sql .= ' ORDER BY ' . $pagination['order'] . ' ' . $pagination['order_dir'] . '';

        $start = ($pagination['num_page'] - 1) * $pagination['rpp'];
        $sql .= ' LIMIT ' . $start . ', ' . $pagination['rpp'] . '';
//$txt = 'Final sql '.$sql.PHP_EOL; fwrite($myfile, $txt);
        $this->db->query($sql);
        if (sizeof($where_filters)) {
            foreach ($where_filters as $filter_name => $filter_data) {
                $this->db->bind(':' . $filter_name, $list_filters[$filter_name]['value']);
            }
        }
//$txt = ('.$sql.').PHP_EOL; fwrite($this->myfile, $txt);
        $rows = $this->db->resultset();

        return array($rows, $count);
    }
}
