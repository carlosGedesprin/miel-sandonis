<?php

namespace src\controller\entity\repository;


/**
 * Trait spammer
 * @package entity
 */
trait spammerRepositoryController
{
    /**
     *
     * Get spammer from his name
     *
     */
    public function getSpammerbyName( $name )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Name ==========> ('.$name.')'.PHP_EOL; fwrite($this->myfile, $txt);
        if ( !$name ) return false;

        //$spammers = $this->getAll( NULL, " ( `name` LIKE '%" . $name . "%' )" );
        $spammers = $this->getAll( ['name' => $name] );
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return ( sizeof( $spammers ) )? true : false;
    }

    /**
     *
     * Get spammer from his email
     *
     */
    public function getSpammerbyEmail( $email )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Email ==========> ('.$email.')'.PHP_EOL; fwrite($this->myfile, $txt);
        if ( !$email ) return false;

        //$spammers = $this->getAll( NULL, " ( `email` LIKE '%" . $email . "%' )" );
        $spammers = $this->getAll( ['email' => $email] );
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return ( sizeof( $spammers ) )? true : false;
    }

    /**
     *
     * Get spammer from his text
     *
     */
    public function getSpammerbyText( $text )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Text ==========> ('.$text.')'.PHP_EOL; fwrite($this->myfile, $txt);
        if ( !$text ) return false;

        $found = false;
        $spamm_texts = $this->getAll( NULL, ' WHERE text is NOT NULL' );
//$txt = 'Spamm texts ==============>'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $spamm_texts, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        $text = strtolower( $text );
        foreach ( $spamm_texts as $spamm_text )
        {
//            $spamm_text = strtolower( $spamm_text );
//$txt = 'Spamm text ===> '.$text.' Spam pattern ==> '.$spamm_text.PHP_EOL; fwrite($this->myfile, $txt);
            if ( str_contains( $text, $spamm_text['text'] ) )
            {
//$txt = 'Is a Spamm text? YES'.PHP_EOL; fwrite($this->myfile, $txt);
                $found = true;
                break;
            }
        }
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $found;
    }
}
