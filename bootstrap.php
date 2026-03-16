<?php
/**
*
* bootstrap.php
*
*/
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once __DIR__ . '/vendor/autoload.php';

/**
* Genera el gestor de entidades
*
* @return Doctrine\ORM\EntityManager
*/
function getEntityManager()
{
	// Cargar configuracion conexion
	$dbParams = array(
		'host'		=> $_ENV['dbal_host'],
		'port'		=> $_ENV['dbal_port'],
		'dbname'	=> $_ENV['dbal_dbname'],
		'user'		=> $_ENV['dbal_user'],
		'password'	=> $_ENV['dbal_password'],
		'driver'	=> $_ENV['dbal_driver'],
		'charset'	=> $_ENV['dbal_charset'],		
	);
	
	$config = Setup::createAnnotationMetadataConfiguration(
		array($_ENV['ENTITY_DIR']),	// path to mapped entities
		$_ENV['DEBUG'],				// developer mode
		ini_get('sys_temp_dir'),	// Proxy dir
		null,						// Cache implementation
		false						// use Simple Annotation Reader
	);
	$config->setAutoGenerateProxyClasses(true);
	if ( $_ENV['DEBUG'] )
	{
		$config->setSQLLogger(new \Doctrine\DBAL\Logging\EchoSQLLogger());
	}
	
	return EntityManager::create($dbParams, $config);
}