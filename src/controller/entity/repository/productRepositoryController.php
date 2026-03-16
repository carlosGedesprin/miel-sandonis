<?php

namespace src\controller\entity\repository;

use \src\controller\entity\planController;
use \src\controller\entity\quoteController;
use \src\controller\entity\websitePPVController;

use DateTime;
use DateTimeZone;
use DateInterval;
use Exception;

/**
 * Trait product
 * @package entity
 */
trait productRepositoryController
{
    /**
     *
     * Calculate price per product type
     */
    public function setPricePerProductType()
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Product id '.$this->getId().' price '.$this->getPrice().PHP_EOL; fwrite($this->myfile, $txt);
        switch ( $this->getProductType() )
        {
            case '1':
                // Consultancy
                break;
            case '2':
                // Automation - Setup
                break;
            case '3':
                // Automation - Renewal
                break;
        }
//$txt = 'Product price ===============> '.$this->getPrice().PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     *  Calculates date_start and date_end when renewal
     *
     * @param $date_start string Date to start calculation, format 'Y-m-d'
     *
     * @return array date_start and date_end dateTime object
     * @throws Exception
     */
    public function calc_renew_dates( $date_start=NULL )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Date start ('.$date_start.')'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Product =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $time_zone = $this->db->fetchField('config', 'config_value', ['config_name' => 'time_zone']);

        $today = ( $date_start != NULL )?  DateTime::createFromFormat('Y-m-d', $date_start, new DateTimeZone($time_zone) ) : new DateTime('now', new DateTimeZone($time_zone));
//$txt = 'Today ========>'.$today->format('Y-m-d').PHP_EOL; fwrite($this->myfile, $txt);

        $start_date = $today;
//$txt = 'Date Start ========>'.$start_date->format('Y-m-d').PHP_EOL; fwrite($this->myfile, $txt);

        $tmstmp_start = strtotime($start_date->format('Y-m-d'));

        $start_year = date('Y', $tmstmp_start);
        $start_month = date('m', $tmstmp_start);

        $tstmp_first_day_start = strtotime($start_year."-".$start_month."-1");
//$txt = 'First day of the month of Date start  ========>'.$tstmp_first_day_start.PHP_EOL; fwrite($this->myfile, $txt);

        switch ( $this->getPeriod() )
        {
            case 'T':
                $months = '36';
                break;
            case 'B':
                $months = '24';
                break;
            case 'Y':
                $months = '12';
                break;
            case 'M':
                $months = '1';
                break;
        }
        $tstmp_first_day_end = strtotime( '+'.$months.' months', $tstmp_first_day_start );
//$txt = 'First day next month ========>'.$tstmp_first_day_end.PHP_EOL; fwrite($this->myfile, $txt);

        if( (int) date('t', $tstmp_first_day_end) < (int) date('d', $tmstmp_start) && (int) date('d', $tmstmp_start) == (int) date('t', $tmstmp_start))
        {
            $end_date_format = date("Y-m-t", $tstmp_first_day_end);
        }
        else
        {
            $start_day_int = (int) $start_month = date('d', $tmstmp_start);
            $sum_days_diff = $start_day_int - 1;
            $end_date_format = date("Y-m-d", strtotime( '+'.abs($sum_days_diff).' days', $tstmp_first_day_end ) );
        }
        $end_date = new DateTime( $end_date_format, new DateTimeZone($time_zone) );
//$txt = 'New date end ========>'.$end_date->format('Y-m-d').PHP_EOL; fwrite($this->myfile, $txt);

//$txt = 'Dates returned start ==>'.$start_date->format('d-m-Y').' end ==>'.$end_date->format('d-m-Y').PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return array( $start_date, $end_date );
    }
}
