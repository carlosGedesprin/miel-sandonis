<?php
namespace src\util\utils;

/**
 * Trait domain
 * @package Utils
 */
trait domain
{
    /**
     *  Sanitize domain name
     */
    public function sanitizeDomainName( $name )
    {
        // 1. Normalizar a minúsculas
        $name = strtolower($name);

        // 2. Quitar acentos y caracteres especiales
        $name = iconv('UTF-8', 'ASCII//TRANSLIT', $name);

        // 3. Reemplazar espacios y símbolos por guiones
        $name = preg_replace('/[^a-z0-9-]+/', '-', $name);

        // 4. Quitar guiones repetidos
        $name = preg_replace('/-+/', '-', $name);

        // 5. Quitar guiones al inicio y al final
        $name = trim($name, '-');

        // 6. Truncar etiquetas a 63 caracteres
        $labels = explode('.', $name);
        foreach ($labels as &$label) {
            $label = substr($label, 0, 63);
        }
        $name = implode('.', $labels);

        // 7. Truncar dominio completo a 253 caracteres
        $name = substr($name, 0, 253);

        return $name;
    }

    /**
     * Validating existing dns records of a domain
     *
     * https://www.php.net/manual/es/function.checkdnsrr.php
     */
    private function validate_domain_dns( $domain_name )
    {
//$myfile = fopen(APP_ROOT_PATH.'/var/logs/4_utils_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($myfile, $txt);
//$txt = PHP_EOL.'Domain Name ==>'.$domain_name.PHP_EOL; fwrite($myfile, $txt);

        $domain_name = idn_to_ascii($domain_name, IDNA_NONTRANSITIONAL_TO_ASCII);
//$txt = PHP_EOL.'domain name IND '.$domain_name.PHP_EOL; fwrite($myfile, $txt);

        // Adding a trailing dot to make it a fqdn (fully qualified domain name)
        if ( checkdnsrr($domain_name.'.', 'A') || checkdnsrr($domain_name.'.', 'CNAME') || checkdnsrr($domain_name.'.', 'NS') )
        {
//$txt = PHP_EOL.'dns ok ==> OK '.PHP_EOL; fwrite($myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($myfile, $txt);
            return true;
        }
        else
        {
//$txt = PHP_EOL.'dns ko ==> KO'.PHP_EOL; fwrite($myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($myfile, $txt);
            return false;
        }
    }
}
