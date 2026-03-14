<?php

namespace src\controller\entity\repository;


/**
 * Trait paymentType
 * @package entity
 */
trait paymentTypeRepositoryController
{
    /**
     *
     * Get method from db
     */
    public static function getPaymentTypeMethod( $db, $payment_type )
    {
        return $db->fetchField('payment_type', 'method', [ 'id' => $payment_type]);
    }

    /**
     *
     * Return options list for news
     */
    public function getOrdinalOptionsList( $action, $value_selected )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $news = $this->getAll( NULL, ' ORDER BY `ordinal` ASC' );
        $last_news = sizeof( $news ) - 1;
        $last_ordinal = $news[$last_news]['ordinal'];
        $options_list = '';

        if ( $action == 'delete' )
        {
            $options_list .= '<option value="'.$value_selected.'" >'.$this->getTitle().'</option>';
        }
        else
        {
            foreach ( $news as $key => $value )
            {
                if ( $key == 0 && $value_selected != 10 )
                {
                    $options_list .= '<option value="5">'.'-----> '.( ( $action == 'add' )? '+' : '<<' ).'</option>';
                }

                $options_list .= '<option value="'.$value['ordinal'].'"'.(( $value_selected == $value['ordinal'])? ' selected="selected" ' : '').' disabled="disabled" >';
                $options_list .= $value['name'];
//                $options_list .= $value['title'].' '.$key.' '.$last_section.' '.$value['ordinal'].' '.$value_selected.' '.$last_ordinal;
                $options_list .= '</option>';

                if ( $value_selected == $value['ordinal'] && $value_selected == $last_ordinal )
                {
                    $options_list .= '<option value="'.($value['ordinal'] + 5).'">'.'-----> '.( ( $action == 'add' )? '+' : '<<' ).'</option>';
                }
                else
                {
                    $options_list .= '<option value="'.($value['ordinal'] + 5).'">'.'-----> '.( ( $action == 'add' )? '+' : '<<' ).'</option>';
                }
            }
        }
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $options_list;
    }

    /**
     *
     * Return options list for news
     */
    public function reOrderOrdinals()
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $news = $this->getAll( NULL, ' ORDER BY `ordinal` ASC' );
//$txt = 'News ========== '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($news, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        $order = 10;

        foreach ( $news as $key => $value )
        {
            $this->getRegbyId( $value['id'] );
//$txt = 'News ========== '.PHP_EOL; fwrite($this->myfile, $txt);
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
