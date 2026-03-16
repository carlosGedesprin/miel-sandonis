<?php
namespace src\util\utils;

/**
 * Trait user
 * @package Utils
 */
trait user
{
    /* --------------------------------------------------------------------------------------------------------------------------------------------------*/
    /**
     * Get the user row
     *
     * @param $user     User id
     * @return string   User record
     */
    public function getUserDetails( $user )
    {
        $row = $this->db->fetchOne('user', '*', ['user' => $user]);
        $details = $this->getLetterSendTo($row);
        unset($row);
        return $details;
    }

    /**
     * Get the user name
     *
     * @param $id       User id
     * @return string   Name of the user, 'System' if id = 0
     */
    public function getUserName( $id )
    {
        if ( $id == '0' )
        {
            $name = 'System';
        }
        else
        {
            $name = $this->db->fetchField('user_profile', 'name', ['user' => $id]);
        }
        return $name;
    }

    /**
     * Get the user's locale
     *
     * @param $id       User id
     * @return mixed    locale
     */
    public function getUserLocale( $id )
    {
//$myfile = fopen(APP_ROOT_PATH.'/var/logs/util_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = 'util_'.__FUNCTION__.' start ==============================================================='.PHP_EOL; fwrite($myfile, $txt);
//        $user_locale = $this->db->fetchField('user', 'locale', ['id' => $id]);
//$txt = 'User '.$id.' locale ==>'.$user_locale.PHP_EOL.PHP_EOL; fwrite($myfile, $txt);
//        return $user_locale;
        return $this->db->fetchField('user', 'locale', ['id' => $id]);
    }

    /**
     * Get the user's account
     *
     * @param $id       User id
     * @return mixed    Account id
     */
    public function getUserAccount( $id )
    {
        return $this->db->fetchField('user', 'account', ['id' => $id]);
    }

    /**
     * Get the user's group
     *
     * @param $id       User id
     * @return mixed    Group id
     */
    public function getUserGroup( $id )
    {
//$myfile = fopen(APP_ROOT_PATH.'/var/logs/util_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = 'util_'.__FUNCTION__.' start ==============================================================='.PHP_EOL; fwrite($myfile, $txt);
        $account =  $this->db->fetchField('user', 'account', ['id' => $id]);
//$txt = 'User '.$id.' customer ==> '.$account.PHP_EOL.PHP_EOL; fwrite($myfile, $txt);
        $user_group = $this->db->fetchField('account', 'group', ['id' => $account]);
//$txt = 'Group ==> '.$user_group.PHP_EOL.PHP_EOL; fwrite($myfile, $txt);
        return $user_group;
    }

    /**
     * Get the user's by group
     *
     * @param $id       Group id
     * @return mixed    Users id
     */
    public function getUserbyGroup( $group )
    {
//$myfile = fopen(APP_ROOT_PATH.'/var/logs/util_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = 'util_'.__FUNCTION__.' start ==============================================================='.PHP_EOL; fwrite($myfile, $txt);
//$txt = 'Group '.$group.PHP_EOL.PHP_EOL; fwrite($myfile, $txt);
        $users = array();

        $accounts =  $this->db->fetchAll('account', 'id', ['group' => $group]);
//$txt = 'Accounts ========>' . PHP_EOL . PHP_EOL; fwrite($myfile, $txt);
//fwrite($myfile, print_r($accounts, TRUE));
        
        foreach( $accounts as $key => $value )
        {
            $users_temp =  $this->db->fetchAll('user', 'id', ['account' => $value['id']]);
            foreach( $users_temp as $key_temp => $value_temp )
            {
                $users[] = $value_temp['id'];
            }
        }
//$txt = 'Group ==> '.$user_group.PHP_EOL.PHP_EOL; fwrite($myfile, $txt);
        return $users;
    }

    /**
     * Check if user is main in account
     *
     * @param $id       User id
     * @return boolean  user is main
     */
    public function isUserMainInAccount( $user_id )
    {
        $account =  $this->db->fetchField('user', 'account', ['id' => $user_id]);
        $main_user = $this->db->fetchField('account', 'main_user', ['id' => $account]);
        return ( $user_id == $main_user)? true : false;
    }

    /**
     * Get the user's key
     *
     * @param $id      User id
     * @return mixed   User key
     */
    public function getUserKey( $id )
    {
//$myfile = fopen(APP_ROOT_PATH.'/var/logs/util_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = 'util_'.__FUNCTION__.' start ==============================================================='.PHP_EOL; fwrite($myfile, $txt);
//$txt = 'User '.$id.' key ==> '.($this->db->fetchField('user', 'user_key', ['id' => $id])).PHP_EOL.PHP_EOL; fwrite($myfile, $txt);
        return $this->db->fetchField('user', 'user_key', ['id' => $id]);
    }

    /**
     * Get the user's account key
     *
     * @param $id      User id
     * @return mixed   Account key
     */
    public function getUserAccountKey( $id )
    {
        $account = $this->db->fetchField('user', 'account', ['id' => $id]);
        return $this->db->fetchField('account', 'account_key', ['id' => $account]);
    }

    /**
     *  Send user to api
     */
    public function edit_user_api( $user, $action )
    {
//$myfile = fopen(APP_ROOT_PATH.'/var/logs/util_'.__FUNCTION__.'_'.$action.'.txt', 'w') or die('Unable to open file!');
//$txt = 'util_'.__FUNCTION__.' start ==============================================================='.PHP_EOL; fwrite($myfile, $txt);
//$txt = 'User received ========>' . PHP_EOL . PHP_EOL; fwrite($myfile, $txt);
//fwrite($myfile, print_r($user, TRUE));

        $user['franchise'] = $_ENV['domain'];
        $user['account_key'] = $this->getAccountKey($user['account']);
        unset($user['account']);
//$txt = 'User before sending ========>' . PHP_EOL . PHP_EOL; fwrite($myfile, $txt);
//fwrite($myfile, print_r($user, TRUE));

        // Call endpoint
        $url_to_call = $this->db->fetchfield('config', 'config_value', ['config_name' => 'cdn_domain']).'/api/user_'.$action;
        $response = $this->send_to_api( $url_to_call, $user);

//$txt = 'Response ========>'.PHP_EOL . PHP_EOL; fwrite($myfile, $txt);
//fwrite($myfile, print_r($response, TRUE));

//$txt = 'util_'.__FUNCTION__.' end ==============================================================='.PHP_EOL; fwrite($myfile, $txt);
//fclose($myfile);
        return $response;
    }

    /**
     *  Send user profile to api
     */
    public function edit_user_profile_api( $user_profile, $action )
    {
//$myfile = fopen(APP_ROOT_PATH.'/var/logs/util_'.__FUNCTION__.'_'.$action.'.txt', 'w') or die('Unable to open file!');
//$txt = 'util_'.__FUNCTION__.' start ==============================================================='.PHP_EOL; fwrite($myfile, $txt);
//$txt = 'User profile received ========>' . PHP_EOL . PHP_EOL; fwrite($myfile, $txt);
//fwrite($myfile, print_r($user_profile, TRUE));

        $user_profile['franchise'] = $_ENV['domain'];
        $user_profile['user_key'] = $this->getUserKey($user_profile['user']);
        unset($user_profile['user']);
//$txt = 'User before sending ========>' . PHP_EOL . PHP_EOL; fwrite($myfile, $txt);
//fwrite($myfile, print_r($user_profile, TRUE));
        $url_to_call = $this->db->fetchfield('config', 'config_value', ['config_name' => 'cdn_domain']).'/api/user_profile_'.$action;
        $response = $this->send_to_api( $url_to_call, $user_profile);

//$txt = 'Response ========>'.PHP_EOL . PHP_EOL; fwrite($myfile, $txt);
//fwrite($myfile, print_r($response, TRUE));

//$txt = 'util_'.__FUNCTION__.' end ==============================================================='.PHP_EOL; fwrite($myfile, $txt);
//fclose($myfile);
        return $response;
    }
}
