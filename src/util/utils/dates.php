<?php
namespace src\util\utils;

use Exception;

/**
 * Trait dates
 * @package Utils
 */
trait dates
{
    /**
     * Converts date into date-time object.
     *
     * @param $date     DD-MM-YYYY H:i:s DD/MM/YYYY H:i:s DD-MM-YYYY DD/MM/YYYY
     * @return date-time object
     */
    public function date_to_object( $date='' )
    {
//$myfile = fopen(APP_ROOT_PATH.'/var/logs/debug_utils_date_object.txt', 'a+') or die('Unable to open file!');
//$txt = PHP_EOL.'utils date_to_object start ==============================================================='.PHP_EOL; fwrite($myfile, $txt);
//$txt = 'date ===> ('.$date.')'.PHP_EOL; fwrite($myfile, $txt);

        if ( $date == NULL || $date == '' || $date == '0000-00-00 00:00:00' || $date == '00-00-0000 00:00:00' || $date == '00/00/0000 00:00:00' || $date == '00-00-0000' || $date == '00/00/0000')
        {
            $result = false;
        }
        else
        {
            $date = trim($date);

            $_year = '(19|20)[0-9]{2}'; // Accept also years with 2 digits
            $_month = '(0[1-9]|1[012])';
            $_day = '(0[1-9]|[12][0-9]|3[01])';
            $_hour = '(2[0-3]|[0][0-9]|1[0-9])';
            $_minute = '([0-5][0-9])';
            $_second = '([0-5][0-9])';
            $match_short = '/^'.$_day.'-'.$_month.'-'.$_year.'$/';
            $match_long_no_seconds = '/^'.$_day.'-'.$_month.'-'.$_year.' '.$_hour.':'.$_minute.'$/';
            $match_long = '/^'.$_day.'-'.$_month.'-'.$_year.' '.$_hour.':'.$_minute.':'.$_second.'$/';

            $date = str_replace('/', '-', $date);
//$txt = 'date replaced ===>'.$date.PHP_EOL; fwrite($myfile, $txt);

            if ( preg_match($match_short, $date) )
            {
//$txt = 'date is short ==> '.$date.PHP_EOL; fwrite($myfile, $txt);
                $date .= ' 00:00:00';
//$txt = 'date is corrected with time ==> '.$date.PHP_EOL; fwrite($myfile, $txt);
            }
            if ( preg_match($match_long_no_seconds, $date) )
            {
//$txt = 'date has no seconds ==> '.$date.PHP_EOL; fwrite($myfile, $txt);
                $date .= ':00';
//$txt = 'date is corrected with seconds ==> '.$date.PHP_EOL; fwrite($myfile, $txt);
            }
            if ( preg_match($match_long, $date) )
            {
//$txt = 'date matches ==> '.$date.PHP_EOL; fwrite($myfile, $txt);
//$txt = 'Date to convert in object is: '.$date.PHP_EOL; fwrite($myfile, $txt);
                try
                {
//$txt = 'Tried long date with seconds '.$date.PHP_EOL; fwrite($myfile, $txt);
                    $result = \DateTime::createFromFormat('d-m-Y H:i:s', $date, new \DateTimeZone($_ENV['time_zone']));
//$txt = 'Date is object '.PHP_EOL; fwrite($myfile, $txt);
                }
                catch (Exception $e)
                {
                    try
                    {
//$txt = 'Tried long date without seconds '.$date.PHP_EOL; fwrite($myfile, $txt);
                        $result = \DateTime::createFromFormat('d-m-Y H:i', $date, new \DateTimeZone($_ENV['time_zone']));
//$txt = 'Date is object '.PHP_EOL; fwrite($myfile, $txt);
                    }
                    catch (Exception $e)
                    {
                        // Sólo con propósitos de demostración...
//fwrite($myfile, print_r( \DateTime::getLastErrors(), TRUE));
//print_r(DateTime::getLastErrors());
                        // La forma real orientada a objetos de hacer esto es
                        // echo $e->getMessage();
//$txt = 'Date with errors '.$e->getMessage().PHP_EOL;
//fwrite($myfile, $txt);
//$txt = 'Date errors '.$e->getMessage().PHP_EOL;
//fwrite($myfile, $txt);
                        $result = false;
                    }
                }
            }
            else
            {
//$txt = 'date is wrong match '.$date.PHP_EOL; fwrite($myfile, $txt);
                $result = false;
            }
        }
//$txt = 'utils date_to_object end ==============================================================='.PHP_EOL; fwrite($myfile, $txt);
//fclose($myfile);
        return $result;
    }

    /**
     * Full date to database.
     *
     * @param $date     DD-MM-YYYY H:i:s
     * @return string   YYYY-MM-DD H:i:s
     */
    public function full_date_to_db( $date )
    {

        if ( $date == NULL || $date == '' || $date == '00-00-0000 00:00:00' )
        {
            return '';
        }
        else
        {
            $match = '/^[0-9](2)-[0-9](2)-[0-9](4) [0-9](1,2):[0-9](1,2):[0-9](1,2)$/';
            if ( !preg_match($match, $date) )
            {
                $date = \DateTime::createFromFormat('d-m-Y H:i:s', $date, new \DateTimeZone($_ENV['time_zone']));
                return $date->format('Y-m-d H:i:s');
            }
            else
            {
                $caller = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
                $caller = ' File:'.$caller[0]['file'].' Function: '.$caller[0]['function'].' Line: '.$caller[0]['line'];
                $this->logger_err->error('*************************************************************************');
                $this->logger_err->error('* preg_match result ('.preg_match($match, $date).')');
                $this->logger_err->error('* Error in '.__METHOD__.' caller ('.$caller);
                $this->logger_err->error('* Expected DD-MM-YYYY H:i:s but '.$date.')');
                $this->logger_err->error('*************************************************************************');
                return '';
            }
        }
    }

    /**
     * Full date from database.
     *
     * @param $date     YYYY-MM-DD H:i:s
     * @return string   DD-MM-YYYY H:i:s
     */
    public function full_db_to_date( $date )
    {

        if ( $date == NULL || $date == '' || $date == '0000-00-00 00:00:00' )
        {
            return '';
        }
        else
        {
            $match = '/^[0-9](4)-[0-9](2)-[0-9](2) [0-9](1,2):[0-9](1,2):[0-9](1,2)$/';
            if ( !preg_match($match, $date) )
            {
                $var = array(
                    'year' => substr( $date, 0, 4),
                    'month' => substr( $date, 5, 2),
                    'day' => substr( $date, 8, 2),
                    'hour' => substr( $date, 11, 2),
                    'minute' => substr( $date, 14, 2),
                    'second' => substr( $date, 17, 2),
                );
                //$date = \DateTime::createFromFormat('Y-m-d H:i:s', $date_temp, new \DateTimeZone($_ENV['time_zone']));
                $tmp = $var['day'].'-'.$var['month'].'-'.$var['year'].' '.$var['hour'].':'.$var['minute'].':'.$var['second'];
                $date_temp = $this->date_to_object( $tmp );
                return $date_temp->format('d-m-Y H:i:s');
            }
            else
            {
                $caller = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
                $caller = ' File:'.$caller[0]['file'].' Function: '.$caller[0]['function'].' Line: '.$caller[0]['line'];
                $this->logger_err->error('*************************************************************************');
                $this->logger_err->error('* preg_match result ('.preg_match($match, $date).')');
                $this->logger_err->error('* Error in '.__METHOD__.' caller ('.$caller);
                $this->logger_err->error('* Expected YYYY-MM-DD H:i:s but '.$date.')');
                $this->logger_err->error('*************************************************************************');
                return '';
            }
        }
    }

    /**
     * Date to database.
     *
     * @param $date     DD-MM-YYYY
     * @return string   YYYYMMDD
     */
    public function date_to_db( $date )
    {

        if ( $date == NULL || $date == '' || $date == '00-00-0000' )
        {
            return '';
        }
        else
        {
            $match = '/^[0-9](1,2)-[0-9](1,2)-[0-9](2,4)$/';
            if ( preg_match($match, $date) )
            {
                $date = \DateTime::createFromFormat('d-m-Y', $date, new \DateTimeZone($_ENV['time_zone']));
                return $date->format('Ymd');
            }
            else
            {
                $caller = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
                $caller = ' File:'.$caller[0]['file'].' Function: '.$caller[0]['function'].' Line: '.$caller[0]['line'];
                $this->logger_err->error('*************************************************************************');
                $this->logger_err->error('* preg_match result ('.preg_match($match, $date).')');
                $this->logger_err->error('* Error in '.__METHOD__.' caller ('.$caller);
                $this->logger_err->error('* Expected DD-MM-YYYY H:i:s but '.$date.')');
                $this->logger_err->error('*************************************************************************');
                return '';
            }
        }
    }

    /**
     * Date from database.
     *
     * @param string $date     YYYYMMDD
     * @return string   DD-MM-YYYY
     */
    public function db_to_date( $date )
    {

        if ( $date == NULL || $date == '' || $date == '00-00-0000' )
        {
            return '';
        }
        else
        {
            $match = '/^[0-9](8)$/';
            if ( preg_match($match, $date) )
            {
                $var = array(
                    'year' => substr( $date, 0, 4),
                    'month' => substr( $date, 4, 2),
                    'day' => substr( $date, 6, 2),
                );

                return implode('-', array_reverse($var));
            }
            else
            {
                $caller = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
                $caller = ' File:'.$caller[0]['file'].' Function: '.$caller[0]['function'].' Line: '.$caller[0]['line'];
                $this->logger_err->error('*************************************************************************');
                $this->logger_err->error('* preg_match result ('.preg_match($match, $date).')');
                $this->logger_err->error('* Error in '.__METHOD__.' caller ('.$caller);
                $this->logger_err->error('* Expected YYYYMMDD but '.$date.')');
                $this->logger_err->error('*************************************************************************');
                return '';
            }
        }
    }

    /**
     * Date from database.
     *
     * @param string $date YYYY-MM-DD
     * @return string   DD-MM-YYYY
     */
    public function short_date_from_db( $date )
    {

        if ( $date == NULL || $date == '' || $date == '0000-00-00' )
        {
            return '';
        }
        else
        {
            $match = '/^[0-9](4)-[0-9](2)-[0-9](2)$/';
            if ( preg_match($match, $date) )
            {
                $var = array(
                    'year' => substr( $date, 0, 4),
                    'month' => substr( $date, 5, 2),
                    'day' => substr( $date, 8, 2),
                );
                $tmp = $var['day'].'-'.$var['month'].'-'.$var['year'];
                $date_temp = $this->date_to_object( $tmp );
                return $date_temp->format('d-m-Y');
            }
            else
            {
                $caller = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
                $caller = ' File:'.$caller[0]['file'].' Function: '.$caller[0]['function'].' Line: '.$caller[0]['line'];
                $this->logger_err->error('*************************************************************************');
                $this->logger_err->error('* preg_match result ('.preg_match($match, $date).')');
                $this->logger_err->error('* Error in '.__METHOD__.' caller ('.$caller);
                $this->logger_err->error('* Expected YYYY-MM-DD but '.$date.')');
                $this->logger_err->error('*************************************************************************');
                return '';
            }
        }
    }

      /**
     * Date from database.
     *
     * @param string $date DD-MM-YYYY
     * @return string   YYYY-MM-DD
     */
    public function short_date_to_db( $date )
    {

        if ( $date == NULL || $date == '' || $date == '00-00-0000' )
        {
            return '';
        }
        else
        {
            $match = '/^[0-9](1,2)-[0-9](1,2)-[0-9](2,4)$/';
            if ( preg_match($match, $date) )
            {
                $date = \DateTime::createFromFormat('d-m-Y', $date, new \DateTimeZone($_ENV['time_zone']));
                return $date->format('Y-m-d');
                /*
                $var = array(
                    'day' => substr( $date, 0, 2),
                    'month' => substr( $date, 3, 2),
                    'year' => substr( $date, 6, 4),
                );
                $tmp = $var['year'].'-'.$var['month'].'-'.$var['day'];
                $date_temp = $this->date_to_object( $tmp );
                return $date_temp->format('Y-m-d');
                */
            }
            else
            {
                $caller = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
                $caller = ' File:'.$caller[0]['file'].' Function: '.$caller[0]['function'].' Line: '.$caller[0]['line'];
                $this->logger_err->error('*************************************************************************');
                $this->logger_err->error('* preg_match result ('.preg_match($match, $date).')');
                $this->logger_err->error('* Error in '.__METHOD__.' caller ('.$caller);
                $this->logger_err->error('* Expected DD-MM-YYYY but '.$date.')');
                $this->logger_err->error('*************************************************************************');
                return '';
            }
        }
    }

    /* ----------------------------------- New methods  ----------------------------------- */

    /**
     * Date from ddmmyyyy_to_yyyymmdd.
     *
     * @param string $date DD-MM-YYYY
     * @return string   YYYY-MM-DD
     */
    public function ddmmyyyy_to_yyyymmdd( $date )
    {

        if ( $date == NULL || $date == '' || $date == '00-00-0000' )
        {
            return '';
        }
        else
        {
            $_year = '(19|20)[0-9]{2}'; // Accept also years with 2 digits
            $_month = '(0[1-9]|1[012])';
            $_day = '(0[1-9]|[12][0-9]|3[01])';

            $match = '/^'.$_day.'-'.$_month.'-'.$_year.'$/';
            if ( preg_match($match, $date) )
            {
                $date = \DateTime::createFromFormat('d-m-Y', $date, new \DateTimeZone($_ENV['time_zone']));
                return $date->format('Y-m-d');
            }
            else
            {
                $caller = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
                $caller = ' File:'.$caller[0]['file'].' Function: '.$caller[0]['function'].' Line: '.$caller[0]['line'];
                $this->logger_err->error('*************************************************************************');
                $this->logger_err->error('* preg_match result ('.preg_match($match, $date).')');
                $this->logger_err->error('* Error in '.__METHOD__.' caller ('.$caller);
                $this->logger_err->error('* Expected DD-MM-YYYY but '.$date.')');
                $this->logger_err->error('*************************************************************************');
                return '';
            }
        }
    }

    /**
     * Date from yyyymmdd_to_ddmmyyy.
     *
     * @param string $date YYYY-MM-DD
     * @return string   DD-MM-YYYY
     */
    public function yyyymmdd_to_ddmmyyyy( $date )
    {

        if ( $date == NULL || $date == '' || $date == '0000-00-00' )
        {
            return '';
        }
        else
        {
            $_year = '(19|20)[0-9]{2}'; // Accept also years with 2 digits
            $_month = '(0[1-9]|1[012])';
            $_day = '(0[1-9]|[12][0-9]|3[01])';

            $match = '/^'.$_year.'-'.$_month.'-'.$_day.'$/';
            if ( preg_match($match, $date) === 1 )
            {
                $date = \DateTime::createFromFormat('Y-m-d', $date, new \DateTimeZone($_ENV['time_zone']));
                return $date->format('d-m-Y');
            }
            else
            {
                $caller = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
                $caller = ' File:'.$caller[0]['file'].' Function: '.$caller[0]['function'].' Line: '.$caller[0]['line'];
                $this->logger_err->error('*************************************************************************');
                $this->logger_err->error('* preg_match result ('.preg_match($match, $date).')');
                $this->logger_err->error('* Error in '.__METHOD__.' caller ('.$caller);
                $this->logger_err->error('* Expected YYYY-MM-DD but '.$date.')');
                $this->logger_err->error('*************************************************************************');
                return '';
            }
        }
    }

    /**
     * date from ddmmyyyyhis_to_yyyymmddhis.
     *
     * @param $date     DD-MM-YYYY H:i:s
     * @return string   YYYY-MM-DD H:i:s
     */
    public function ddmmyyyyhis_to_yyyymmddhis( $date )
    {
//$myfile = fopen(APP_ROOT_PATH.'/var/logs/utils_dates.txt', 'w') or die('Unable to open file!');
//$txt = 'ddmmyyyyhis_to_yyyymmddhis start ==============================================================='.PHP_EOL; fwrite($myfile, $txt);
//$txt = 'Param date ('.$date.')'; fwrite($myfile, $txt);

        if ( $date == NULL || $date == '' || $date == '00-00-0000 00:00:00' )
        {
            return '';
        }
        else
        {
            $_year = '(19|20)[0-9]{2}'; // Accept also years with 2 digits
            $_month = '(0[1-9]|1[012])';
            $_day = '(0[1-9]|[12][0-9]|3[01])';
            $_hour = '(2[0-3]|[0][0-9]|1[0-9])';
            $_minute = '([0-5][0-9])';
            $_second = '([0-5][0-9])';

            $match = '/^'.$_day.'-'.$_month.'-'.$_year.' '.$_hour.':'.$_minute.':'.$_second.'$/';
//$txt = 'match ('.$match.')'; fwrite($myfile, $txt);
            if ( preg_match($match, $date) === 1 )
            {
                $date = \DateTime::createFromFormat('d-m-Y H:i:s', $date, new \DateTimeZone($_ENV['time_zone']));
                return $date->format('Y-m-d H:i:s');
            }
            else
            {
                $caller = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
                $caller = ' File:'.$caller[0]['file'].' Function: '.$caller[0]['function'].' Line: '.$caller[0]['line'];
                $this->logger_err->error('*************************************************************************');
                $this->logger_err->error('* preg_match result ('.preg_match($match, $date).')');
                $this->logger_err->error('* Error in '.__METHOD__.' caller ('.$caller);
                $this->logger_err->error('* Expected DD-MM-YYYY H:i:s but '.$date.')');
                $this->logger_err->error('*************************************************************************');
                return '';
            }
        }
    }

    /**
     * date from yyyymmddhis_to_ddmmyyyyhis.
     *
     * @param $date     YYYY-MM-DD H:i:s
     * @return string   DD-MM-YYYY H:i:s
     */
    public function yyyymmddhis_to_ddmmyyyyhis( $date )
    {
        if ( $date == NULL || $date == '' || $date == '0000-00-00 00:00:00' )
        {
            return '';
        }
        else
        {
            $_year = '(19|20)[0-9]{2}'; // Accept also years with 2 digits
            $_month = '(0[1-9]|1[012])';
            $_day = '(0[1-9]|[12][0-9]|3[01])';
            $_hour = '(2[0-3]|[0][0-9]|1[0-9])';
            $_minute = '([0-5][0-9])';
            $_second = '([0-5][0-9])';

            $match = '/^'.$_year.'-'.$_month.'-'.$_day.' '.$_hour.':'.$_minute.':'.$_second.'$/';
            if ( preg_match($match, $date) === 1 )
            {
                $date = \DateTime::createFromFormat('Y-m-d H:i:s', $date, new \DateTimeZone($_ENV['time_zone']));
                return $date->format('d-m-Y H:i:s');
            }
            else
            {
                $caller = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
                $caller = ' File:'.$caller[0]['file'].' Function: '.$caller[0]['function'].' Line: '.$caller[0]['line'];
                $this->logger_err->error('*************************************************************************');
                $this->logger_err->error('* preg_match result ('.preg_match($match, $date).')');
                $this->logger_err->error('* Error in '.__METHOD__.' caller ('.$caller);
                $this->logger_err->error('* Expected DD-MM-YYYY H:i:s but '.$date.')');
                $this->logger_err->error('*************************************************************************');
                return '';
            }
        }
    }
}
