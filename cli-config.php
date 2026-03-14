<?php
/**
*
* cli-config.php
*
*/

use Doctrine\ORM\Tools\Console\ConsoleRunner;

define('APP_ROOT_PATH', dirname( __FILE__ ));

require_once APP_ROOT_PATH . '/vendor/autoload.php';

// Carga las variables de entorno
$dotenv = Dotenv\Dotenv::createMutable(APP_ROOT_PATH.'/config', 'config.env');
$dotenv->load();
$dotenv->required(['dbal_host', 'dbal_dbname', 'dbal_port', 'dbal_user', 'dbal_password']);

//echo 'host ('.$_ENV['dbal_host'].') port ('.$_ENV['dbal_port'].') dbname ('.$_ENV['dbal_dbname'].') user ('.$_ENV['dbal_user'].') password ('.$_ENV['dbal_password'].') driver ('.$_ENV['dbal_driver'].') charset ('.$_ENV['dbal_charset'].')';
//echo ' Entity dir ('.$_ENV['ENTITY_DIR'].' ) Debug ('.$_ENV['DEBUG'].')';
//die();

require_once __DIR__ . '/bootstrap.php';

$entityManager = getEntityManager();

return ConsoleRunner::createHelperSet($entityManager);
