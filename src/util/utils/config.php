<?php
namespace src\util\utils;

/**
 * Trait config
 * @package Utils
 */
trait config
{
    /**
     * Get the config value
     *
     * @param string $config_name  Name of the config value searched
     * @return mixed        Value of the config value searched
     */
    public function getConfig( $config_name )
    {
        return $this->db->fetchField('config', 'config_value', ['config_name' => $config_name]);
        //$row = $this->db->fetchOne('config', 'config_value', ['config_name' => $config_name]);
        //return $row['config_value'];
    }

    /**
     * Set the config value
     *
     * @param string $config_name  Name of the config value to be altered
     * @param string $config_value Value to assign to config
     * @return bool         True
     */
    public function setConfig( $config_name, $config_value )
    {
        $this->db->updateArray( 'config', 'config_name', $config_name, ['config_value' => $config_value]);
        return true;
    }

    /**
     * Get actual value and set the next value to a counter field in config table
     *
     * @param string $field    Name of the config value
     * @return mixed    New value
     */
    public function next_config_value( $field )
    {
//$myfile = fopen(APP_ROOT_PATH.'/var/logs/debug_next_config_value.txt', 'a+') or die('Unable to open file!');
//$txt = 'utils next_config_value start ==============================================================='.PHP_EOL;
//fwrite($myfile, $txt);
        $sql = 'UPDATE config SET config_value = (@cur_value := config_value) + 1 WHERE config_name = "'.$field.'"';
        $this->db->querySQL($sql);
        $sql = 'SELECT @cur_value as field';
        $row = $this->db->querySQL($sql);
//fwrite($myfile, print_r($row, TRUE));
//$txt = 'Field => '.$row[0]['field'].PHP_EOL;
//fwrite($myfile, $txt);
//$txt = 'utils next_config_value end ==============================================================='.PHP_EOL;
//fwrite($myfile, $txt);
//fclose($myfile);
        return $row[0]['field'];
    }

}
