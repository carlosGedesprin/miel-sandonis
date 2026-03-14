<?php

namespace src\controller\entity\repository;

use src\controller\entity\langTextController;
use src\controller\entity\langTextNameController;

/**
 * Trait cron
 * @package entity
 */
trait cronRepositoryController
{
    /**
     *
     * Return options list for crons
     */
    public function getOrdinalOptionsList( $action, $periodicity, $value_selected )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Periodicity ========== '.$periodicity.PHP_EOL; fwrite($this->myfile, $txt);
        $lang_text = new langTextController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $lang_text_name = new langTextNameController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $items = $this->getAll( [ 'periodicity' => $periodicity ], ' ORDER BY `ordinal` ASC' );
//$txt = 'Items =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($items, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        $last_item = sizeof( $items ) - 1;
//$txt = 'Last item '.$last_item.PHP_EOL; fwrite($this->myfile, $txt);
        $last_ordinal = $items[$last_item]['ordinal'];
//$txt = 'Last ordinal '.$last_ordinal.PHP_EOL; fwrite($this->myfile, $txt);
        $options_list = '';

        if ( $action == 'delete' )
        {
            $options_list .= '<option value="'.$value_selected.'" >'.$this->getProcess().'</option>';
        }
        else
        {
            $locale = $_SESSION[$this->session->config['cookies_prefix'].'_locale'];
//$txt = 'Locale ===> '.$locale.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($vars, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
            $lang_text->getRegbyLangKey( 'ORDINAL_ADD_HERE', );
            $lang_text_name->getRegbyLangTextAndLang( $lang_text->getId(), $locale );
            $text_add = $lang_text_name->getText();
//$txt = 'Text add ===> '.$text_add.PHP_EOL; fwrite($this->myfile, $txt);
            //$text_add = langTextController::getLangText( $this->utils, $locale,'ORDINAL_ADD_HERE' );
            $lang_text->getRegbyLangKey( 'ORDINAL_MOVE_HERE', );
            $lang_text_name->getRegbyLangTextAndLang( $lang_text->getId(), $locale );
            $text_no_add = $lang_text_name->getText();
//$txt = 'Text no add ===> '.$text_no_add.PHP_EOL; fwrite($this->myfile, $txt);
            //$text_no_add = langTextController::getLangText( $this->utils, $locale,'ORDINAL_MOVE_HERE' );

            $options_list .= '<option value="5">'.'-----> '.( ( $action == 'add' )? '+ ' : '<< ' ).'5'.'</option>';
            foreach ( $items as $key => $value )
            {
//$txt = 'Key '.$key.' value selected '.$value_selected.PHP_EOL; fwrite($this->myfile, $txt);
/*
                if ( $key == 0 && $value_selected == 10 )
                {
                    $options_list .= '<option value="5">'.'-----> '.( ( $action == 'add' )? '+ ' : '<< ' ).($value['ordinal'] + 5).'</option>';
                }
*/

//$txt = 'Process '.$value['id'].' Selected ===> '.( $value_selected == $value['ordinal'])? 'Yes' : 'No'.$periodicity.PHP_EOL; fwrite($this->myfile, $txt);
                $options_list .= '<option value="'.$value['ordinal'].'"'.(( $value_selected == $value['ordinal'])? ' selected="selected" ' : '').' disabled="disabled" >';
                $options_list .= $value['ordinal'].' - '.$value['process'];
//                $options_list .= $value['title'].' '.$key.' '.$last_section.' '.$value['ordinal'].' '.$value_selected.' '.$last_ordinal;
                $options_list .= '</option>';

                if ( $value_selected == $value['ordinal'] && $value_selected == $last_ordinal )
                {
                    $options_list .= '<option value="'.($value['ordinal'] + 5).'">'.'---------------> '.( ( $action == 'add' )? '+ '.$text_add : '<< '.$text_no_add ).' '.($value['ordinal'] + 5).'</option>';
                }
                else
                {
                    $options_list .= '<option value="'.($value['ordinal'] + 5).'">'.'---------------> '.( ( $action == 'add' )? '+ '.$text_add : '<< '.$text_no_add ).' '.($value['ordinal'] + 5).'</option>';
                }
            }
        }
//$txt = 'Items list =========='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = $options_list.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $options_list;
    }

    /**
     *
     * Return options list for crons
     */
    public function reOrderOrdinals( $periodicity )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $items = $this->getAll( [ 'periodicity' => $periodicity ], ' ORDER BY `ordinal` ASC' );
//$txt = 'Items ========== '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($items, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        $order = 10;

        foreach ( $items as $key => $value )
        {
            $this->getRegbyId( $value['id'] );
//$txt = 'Item ========== '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

            $this->setOrdinal( $order );
            $this->persist();

            $order = $order + 10;
//$txt = 'New ordinal ========== '.$order.PHP_EOL; fwrite($this->myfile, $txt);
        }
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return true;
    }
}
