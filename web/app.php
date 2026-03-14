<?php

define('APP_ROOT_PATH', dirname( __FILE__, 2 ));

$autoloader = require_once APP_ROOT_PATH . '/vendor/autoload.php';

require_once APP_ROOT_PATH.'/src/util/constant.php';

$myfile = fopen(APP_ROOT_PATH.'/var/logs/web_app.txt', 'a+') or die('Unable to open file!');
$now = (new \DateTime('now', new \DateTimeZone('Europe/Madrid')))->format('d-m-Y H:i:s');
$txt = PHP_EOL.'====================== app start => '.$now.' ======================'.PHP_EOL;fwrite($myfile, $txt);
//$txt = 'Requested URI ('.$_SERVER['REQUEST_URI'].')'.PHP_EOL;fwrite($myfile, $txt);
//$txt = PHP_EOL.'Headers start =============================================================='.PHP_EOL;fwrite($myfile, $txt);
//fwrite($myfile, print_r(apache_request_headers(), TRUE));
//$txt = PHP_EOL.'Headers end =============================================================='.PHP_EOL;fwrite($myfile, $txt);
//$txt = PHP_EOL.'Server start =============================================================='.PHP_EOL;fwrite($myfile, $txt);
//fwrite($myfile, print_r($_SERVER, TRUE));
//$txt = PHP_EOL.'Server end =============================================================='.PHP_EOL;fwrite($myfile, $txt);
//$txt = 'Declared classes'.PHP_EOL; fwrite($myfile, $txt);
//fwrite($myfile, print_r(get_declared_classes(), TRUE));
//$txt = 'APP ROOT PATH ('.APP_ROOT_PATH.')'.PHP_EOL; fwrite($myfile, $txt);

//foreach (getallheaders() as $name => $value) { $txt = 'Name ('.$name.') Value ('.$value.')'.PHP_EOL; fwrite($myfile, $txt); }

//echo '<pre>';print_r(get_declared_classes());echo '</pre>'; die();
//$txt = (class_exists('Dotenv', true))? 'Class Dotenv exists'.PHP_EOL :  'Class Dotenv NOT exists'.PHP_EOL; fwrite($myfile, $txt);

include 'forbiden_uris.php';

// Load environment vars
use Dotenv\Dotenv;
$dotenv =  Dotenv::createMutable(APP_ROOT_PATH.'/config', 'config.env');
//$envArr = array_map(function ($var) {
//    $values = explode('=', $var);
//    return [$values[0] => $values[1]];
//}, $dotenv->load());
//fwrite($myfile, print_r($envArr, TRUE)); $txt = PHP_EOL; fwrite($myfile, $txt);
$dotenv->load();
$dotenv->required([
    "dbal_driver",
    "dbal_host",
    "dbal_port",
    "dbal_dbname",
    "dbal_user",
    "dbal_password"
]);
//$txt = 'ENV loaded'.PHP_EOL; fwrite($myfile, $txt);
//fwrite($myfile, print_r($_ENV, TRUE)); $txt = PHP_EOL; fwrite($myfile, $txt);

// Using Monolog
// https://stackify.com/php-monolog-tutorial/
// Create a log channel
$logger = new \Monolog\Logger('app');
$logger->pushHandler(new \Monolog\Handler\StreamHandler(APP_ROOT_PATH.'/var/logs/app.log', $logger::DEBUG));
$logger_err = new \Monolog\Logger('err');
$logger_err->pushHandler(new \Monolog\Handler\StreamHandler(APP_ROOT_PATH.'/var/logs/app_err.log', $logger::DEBUG));
// Levels: DEBUG, INFO, NOTICE, WARNING, ERROR, CRITICAL, ALERT, EMERGENCY
//$logger->info('This is a log! ^_^ ');
//$logger->warning('This is a log warning! ^_^ ');
//$logger->error('This is a log error! ^_^ ');
//$txt = 'Monolog loaded'.PHP_EOL; fwrite($myfile, $txt);
//$txt = 'Monolog loaded'.PHP_EOL; fwrite($myfile, $txt);

// Start up actions
use \src\util\startup;
$startup = new startup();
//$txt = 'startup loaded'.PHP_EOL;fwrite($myfile, $txt);

// Connect with the database
use \src\util\db;
$db = new db( $_ENV['dbal_host'], $_ENV['dbal_dbname'], $_ENV['dbal_port'], $_ENV['dbal_user'], $_ENV['dbal_password'], $_ENV['dbal_driver'], $_ENV['dbal_charset'], $logger, $logger_err );
//$txt = 'db loaded'.PHP_EOL;fwrite($myfile, $txt);

// Execute an action on the database (Dev and int environment)
/*
* Add user_key to users
$temp = $db->fetchAll('user', 'id, email');
foreach( $temp as $key => $value )
{
    print_r($value);
    $user_key = md5($value['id'].$value['email']);
    $db->updateArray('user', 'id', $value['id'], ['user_key' => $user_key]);
}
*/
/*
if ( $_ENV['env_env'] == 'dev' || $_ENV['env_env'] == 'int' )
{
    $crypt_options = array('cost' => 12,);
    $password = password_hash('0000', PASSWORD_BCRYPT, $crypt_options);
    // One user
    // 1-SuperAdmin 2-Admin 3-Staff 4-Student 5-Teacher 6-Visitor
    $db->updateArray('user', 'id', '12', ['password' => $password]);
    $temp = $db->fetchAll('user', 'id');
    // All users
    foreach( $temp as $key => $value )
    {
        echo 'New password for '. $value['id'] . ' is ' .$password . '<br />';
        $db->updateArray('user', 'id', $value['id'], ['password' => $password]);
    }
    // Show config values
    print "<pre>";print_r($config);print "</pre>";
    $config = $db->fetchAll('config', 'config_name, config_value' );
    foreach( $config as $key => $value)
    {
        echo $value['config_name'].' => '.$value['config_value'] . '<br />';
    }
}
*/
//TODO:Carlos Error and message handler, call with trigger_error if reqd function msg_handler($errno, $msg_text, $errfile, $errline) functions 4022

// Load util functions
use \src\util\utils;
$utils = new utils( $db, $logger, $logger_err );
//$txt = 'utils loaded'.PHP_EOL;fwrite($myfile, $txt);

// Session actions
use \src\util\session;
$session = new session( $startup, $db, $utils, $logger, $logger_err );
//$txt = 'session lang '.$session->getLanguageCode2a().PHP_EOL;fwrite($myfile, $txt);
//$txt = 'session loaded'.PHP_EOL;fwrite($myfile, $txt);

use \src\util\lang;
$lang_class = new lang( $_ENV, $logger, $logger_err, $startup, $db, $utils, $session );
$lang = $lang_class->getLangTexts();
//$txt = 'Lang loaded'.PHP_EOL;fwrite($myfile, $txt);
//echo print_r($lang, true);

// Start twig engine
use \Twig\Loader\FilesystemLoader;
$loader = new FilesystemLoader(APP_ROOT_PATH.'/app/Resources/views');
// -----------------------------------------------------------------------
// Examples
// -----------------------------------------------------------------------
//$twig = new Twig_Environment($loader, array('cache' => '../var/cache/twig',));
// or
//$twig = new Twig_Environment($loader);
// or to use the dump function in twig
use \Twig\Environment;
use \Twig\Extension\DebugExtension;
use \Twig\Extra\Intl\IntlExtension;
$twig = new Environment($loader, ['debug' => true]); // deprecated
$twig->addExtension(new DebugExtension());
$twig->addExtension(new IntlExtension());
//$twig->addFilter(new \Twig_Simple_Filter, 'html_entity_decode', 'html_entity_decode');
// -----------------------------------------------------------------------
$twig->addGlobal('uri', $startup->getURI());
$twig->addGlobal('env', $_ENV);
$twig->addGlobal('lang', $lang);
$twig->addGlobal('config', $session->config);
$twig->addGlobal('session', $_SESSION);
$twig->addGlobal('cookies', $_COOKIE);
$twig->addGlobal('server', $_SERVER);
// -----------------------------------------------------------------------
require_once APP_ROOT_PATH.'/src/util/twig/twigFilters.php';
require_once APP_ROOT_PATH.'/src/util/twig/twigFunctions.php';
//$txt = 'Twig loaded'.PHP_EOL;fwrite($myfile, $txt);

// -----------------------------------------------------------------------
//session_destroy();
//setcookie($session->config['cookies_prefix']."_Login", "", time() - 10, $session->cookies['path'], $session->cookies['domain']);
// -----------------------------------------------------------------------

// Cron
// Ensure what the cron will do in dev environment
if ( $_ENV['env_env'] == 'dev')
{
    /*
    // Ensure cron is not locked
    $db->querySQL("UPDATE config SET config_value = '' WHERE config_name = 'cron_lock'");

    //$db->querySQL('UPDATE cron SET run = 1 WHERE process = "invoice"');
    ////$db->updateArray('mail_queue', 'id', '17', ['sent' => '0000-00-00 00:00:00']);
    // $db->querySQL('UPDATE cron SET run = 0 WHERE process = "mail"');
    // $db->querySQL('UPDATE print_queue SET printed = "2019-01-01 00:00:00"');
    // //$db->updateArray('print_queue', 'id', '182', ['printed' => '0000-00-00 00:00:00']);
    // $db->querySQL('UPDATE cron SET run = 1 WHERE process = "print"');
    // $db->querySQL('UPDATE cron SET run = 0 WHERE process = "print_ftp"');

    //$db->querySQL('UPDATE mail_queue SET sent = ""');
    */
}

// Execute an action in dev environment
/*
if ( $_ENV['env_env'] == 'dev')
{
    //$myfile = fopen('debug_web_app.txt', 'w') or die('Unable to open file!');
    //$txt = 'app start '.$_ENV['env_env'].'==============================================================='.PHP_EOL;
    //fwrite($myfile, $txt);
    // Create a document for testing
    //    require_once(APP_ROOT_PATH . '/src/util/utils/chargeFunctions.php');
    //    $chargeFunctions = new \Utils\utils\chargeFunctions($session, $db, $lang, $utils);
    //    $chargeFunctions->setCharge( $utils->getChargeData( '28' ) );
    //    $chargeFunctions->create_doc( 'NFPYD' );

    //    $chargeFunctions->create_doc( 'PAP' );
    //    $chargeFunctions->create_doc( 'N1' );
    //    $chargeFunctions->create_doc( 'N225' );
    //    $chargeFunctions->create_doc( 'LBC' );
    //    $chargeFunctions->create_doc( 'N1_ATT' );

    //    $popo = array('N1_1546882726_110.pdf');
    //    echo serialize($popo);

    //$txt = '=================> Doc created is  '.$doc_id.PHP_EOL;
    //fwrite($myfile, $txt);
    //$txt = 'app end ==============================================================='.PHP_EOL;
    //fwrite($myfile, $txt);
    //fclose($myfile);
}
*/

// URL Routing
// Check if the visitor is allowed to see this url
// Set the first param in the url of the pages that require logging in.
// Format array [0] -> 1rst folder in the url [1] -> group_id to belong to
// Actual groups: 1-SuperAdmin 2-Admin 3-Staff 4-Customer 5-Agent 6-Integrator 7-Public sector
$folders = array(
    array('supermanage', [GROUP_SUPER_ADMIN]),
    array('app',[GROUP_SUPER_ADMIN, GROUP_ADMIN, GROUP_STAFF]),
    array('control_panel',[GROUP_CUSTOMER, GROUP_AGENT]),
    //array('pm',[GROUP_SUPER_ADMIN, GROUP_ADMIN, GROUP_STAFF, GROUP_CUSTOMER, GROUP_AGENT, GROUP_INTEGRATOR, GROUP_PUBLIC_SECTOR,  GROUP_CONSULTOR]),
);
$session->checkProtectedURL( $folders );
// if ( $_ENV['env_env'] == 'dev' ) $txt = 'URL checked'.PHP_EOL; fwrite($myfile, $txt);

// Manage routes
/*
 * Improvement: get the key from lang table. With a new context: route
$routesDefinitionsCallback = function (RouteCollector $r) {
    foreach (require __DIR__ . "/es/routes.php" as $route) {
        $r->addRoute($route[0], $route[1], $route[2]);
    }
};
$dispatcher = \FastRoute\simpleDispatcher($routesDefinitionsCallback);
*/
$dispatcher = \FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r)
{
    $r->addRoute(['GET', 'POST'], '/show_message', 'defaultController:showmessageAction');

    // SiteMap
    $r->addRoute('GET', '/sitemap_website.xml', 'sitemapController:sitemapAction');

    // Web
    $r->addRoute(['GET', 'POST'], '/', 'web/webController:indexAction');
    $r->addRoute(['GET', 'POST'], '/home', 'web/webController:indexAction');

    $r->addRoute(['GET', 'POST'], '/templates', 'web/webController:templatesAction');

    $r->addRoute(['GET', 'POST'], '/solutions', 'web/webController:solutionsAction');
    $r->addRoute(['GET', 'POST'], '/soluciones', 'web/webController:solutionsAction');

    $r->addRoute(['GET', 'POST'], '/solution_invoices', 'web/webController:solutionInvoicesAction');
    $r->addRoute(['GET', 'POST'], '/solution_expense_notes', 'web/webController:solutionExpenseNotesAction');
    $r->addRoute(['GET', 'POST'], '/solution_personal_assistant', 'web/webController:solutionPersonalAssistantAction');


    $r->addRoute(['GET', 'POST'], '/sectores', 'web/webController:sectorsAction');
    $r->addRoute(['GET', 'POST'], '/sectors', 'web/webController:sectorsAction');

    $r->addRoute(['GET', 'POST'], '/sector/{sector_slug}', 'web/webController:sectorAction');

    $r->addRoute(['GET', 'POST'], '/about-us', 'web/webController:aboutUsAction');
    $r->addRoute(['GET', 'POST'], '/conocenos', 'web/webController:aboutUsAction');

    $r->addRoute(['GET', 'POST'], '/contacto', 'web/contactController:contactAction');
    $r->addRoute(['GET', 'POST'], '/contact', 'web/contactController:contactAction');

    $r->addRoute(['GET', 'POST'], '/contact-thank-you', 'web/contactController:contactThanksAction');
    $r->addRoute(['GET', 'POST'], '/contacto-gracias', 'web/contactController:contactThanksAction');

    $r->addRoute(['GET', 'POST'], '/baja-mails/{email}/{token}', 'web/securityViewController:unsubscribeAction');

    $r->addRoute(['GET', 'POST'], '/contacto-feria', 'web/contactFairController:contactAction');

    $r->addGroup('/solution', function (FastRoute\RouteCollector $r) {
        $r->addRoute(['GET', 'POST'], '/instagram-grow', 'web/solutionController:solutionInstagramGrowAction');
        $r->addRoute(['GET', 'POST'], '/crecer-en-instagram', 'web/solutionController:solutionInstagramGrowAction');
        $r->addRoute(['GET', 'POST'], '/invoicing', 'web/solutionController:solutionInvoicingAction');
        $r->addRoute(['GET', 'POST'], '/facturacion', 'web/solutionController:solutionInvoicingAction');
        $r->addRoute(['GET', 'POST'], '/personal-assistant', 'web/solutionController:solutionPersonalAssistantAction');
        $r->addRoute(['GET', 'POST'], '/asistente-personal', 'web/solutionController:solutionPersonalAssistantAction');
        $r->addRoute(['GET', 'POST'], '/bookings-agent', 'web/solutionController:solutionBookingsAgentAction');
        $r->addRoute(['GET', 'POST'], '/agente-de-reservas', 'web/solutionController:solutionBookingsAgentAction');
        $r->addRoute(['GET', 'POST'], '/agent-no-show', 'web/solutionController:solutionAgentNoShowAction');
        $r->addRoute(['GET', 'POST'], '/agente-confirmacion-cita', 'web/solutionController:solutionAgentNoShowAction');
        $r->addRoute(['GET', 'POST'], '/linkedin-instagram-scrapping', 'web/solutionController:solutionLinkedinInstagramScrappingAction');
        $r->addRoute(['GET', 'POST'], '/buscador-contactos-linkedin-instagram', 'web/solutionController:solutionLinkedinInstagramScrappingAction');
        $r->addRoute(['GET', 'POST'], '/web-scraping', 'web/solutionController:solutionWebScrapingAction');
        $r->addRoute(['GET', 'POST'], '/obten-informacion-cualquier-sitio-web', 'web/solutionController:solutionWebScrapingAction');
    });

    // Blog
    $r->addGroup('/blog', function (FastRoute\RouteCollector $r) {

        $r->addRoute('GET', '/sitemap_index.xml', 'web/blogViewController:sitemapAction');

        $r->addRoute(['GET', 'POST'], '', 'web/blogViewController:blogAction');
        $r->addRoute(['GET', 'POST'], '/', 'web/blogViewController:blogAction');
        $r->addRoute(['GET', 'POST'], '/categories', 'web/blogViewController:categoriesAction');
        $r->addRoute(['GET', 'POST'], '/categories/', 'web/blogViewController:categoriesAction');
        $r->addRoute(['GET', 'POST'], '/category/{slug}', 'web/blogViewController:categoryAction');
        $r->addRoute(['GET', 'POST'], '/category/{slug}/', 'web/blogViewController:categoryAction');
        $r->addRoute(['GET', 'POST'], '/articles[/{category}[/]]', 'web/blogViewController:articlesAction');
        //$r->addRoute(['GET', 'POST'], '/articles[/{category}/]', 'web/blogViewController:articlesAction');
        $r->addRoute(['GET', 'POST'], '/article/{slug}', 'web/blogViewController:articleAction');
        $r->addRoute(['GET', 'POST'], '/article/{slug}/', 'web/blogViewController:articleAction');
        $r->addRoute(['GET', 'POST'], '/{slug}', 'web/blogViewController:blogSlugAction');
        $r->addRoute(['GET', 'POST'], '/{slug}/', 'web/blogViewController:blogSlugAction');

    });

    //Payments
    $r->addGroup('/payments', function (FastRoute\RouteCollector $r) {

        include 'routes_payments.php';

    });

    $r->addGroup('/api', function (FastRoute\RouteCollector $r) {

        $r->addRoute(['GET', 'POST'], '/get_product', 'api/automationController:getProductAction');

        include 'routes_api_n8n.php';

    });

    $r->addGroup('/ajax', function (FastRoute\RouteCollector $r) {
        // Locations
        $r->addRoute(['GET', 'POST'], '/get_regions/{country:\d+}', 'ajaxController:getregionsAction');
        $r->addRoute(['GET', 'POST'], '/get_cities/{country:\d+}/{region:\d+}', 'ajaxController:getcitiesAction');
        // User
        $r->addRoute(['GET', 'POST'], '/get_user/{id:\d+}', 'ajaxController:getUserAction');
        $r->addRoute(['GET', 'POST'], '/get_user_profile/{id:\d+}', 'ajaxController:getUserProfileAction');

        $r->addRoute('POST', '/user_password', 'ajaxController:user_passwordAction');
        $r->addRoute('POST', '/user_email', 'ajaxController:user_emailAction');

        $r->addRoute(['GET', 'POST'], '/mail_queue/resend/{user:\d+}/{id:\d+}', 'ajaxController:resend_emailAction');
    });

    // Cron
    $r->addGroup('/cron', function (FastRoute\RouteCollector $r) {
        $r->addRoute(['GET', 'POST'], '/{img}', 'cronController:webcronAction');    // Called in any webpage footer
        $r->addRoute(['GET', 'POST'], '/time/{time}', 'cronController:scheduledCronAction');   // To use with OS cron daemon time = minute / hour / day
    });

    // Security
    $r->addRoute('GET', '/webstatus/{status}', 'web/securityViewController:webstatusAction');
    $r->addRoute(['GET', 'POST'], '/login', 'web/securityViewController:loginAction');
    $r->addRoute('GET', '/logout', 'web/securityViewController:logoutAction');

    $r->addRoute('GET', '/session-expired', 'web/securityViewController:expiredAction');
    $r->addRoute('GET', '/activate-user/{token}', 'web/securityViewController:activateUserAction');
    $r->addRoute(['GET', 'POST'], '/forgot-password', 'web/securityViewController:forgotPasswordAction');
    $r->addRoute(['GET', 'POST'], '/forgot_password', 'web/securityViewController:forgotPasswordAction');
    $r->addRoute(['GET', 'POST'], '/change-password/{email}/{token}', 'web/securityViewController:changePasswordAction');
    $r->addRoute(['GET', 'POST'], '/change-password', 'web/securityViewController:changePasswordAction');

    // App
    $r->addGroup('/app', function (FastRoute\RouteCollector $r) {

        include 'routes_app.php';

    });

    //Control Panel
    $r->addGroup('/control_panel', function (FastRoute\RouteCollector $r) {

        include 'routes_control_panel.php';

    });

    // Test
    $r->addGroup('/test', function (FastRoute\RouteCollector $r) {

        include 'routes_test.php';

    });

    // Legal
    $r->addRoute(['GET', 'POST'], '/legal-stuff', 'web/legalController:legalstuffAction');
    $r->addRoute(['GET', 'POST'], '/aviso-legal', 'web/legalController:legalstuffAction');

    $r->addRoute(['GET', 'POST'], '/sales-terms', 'web/legalController:termsandconditionsAction');
    $r->addRoute(['GET', 'POST'], '/terminos-de-venta', 'web/legalController:termsandconditionsAction');

    $r->addRoute(['GET', 'POST'], '/privacy-policy', 'web/legalController:privacypolicyAction');
    $r->addRoute(['GET', 'POST'], '/politica-privacidad', 'web/legalController:privacypolicyAction');

    $r->addRoute(['GET', 'POST'], '/cookies-policy', 'web/legalController:cookiespolicyAction');
    $r->addRoute(['GET', 'POST'], '/politica-cookies', 'web/legalController:cookiespolicyAction');

    $r->addRoute(['GET', 'POST'], '/payment-terms', 'web/legalController:paymenttermsAction');
    $r->addRoute(['GET', 'POST'], '/terminos-pagos', 'web/legalController:paymenttermsAction');

    $r->addRoute(['GET', 'POST'], '/accessibility-statement', 'web/legalController:accessibilityStatementAction');
    $r->addRoute(['GET', 'POST'], '/declaracion-accesibilidad', 'web/legalController:accessibilityStatementAction');

    // {id} must be a number (\d+)
    //$r->addRoute('GET', '/user/{id:\d+}', 'get_user_handler');
    // The /{title} suffix is optional
    //$r->addRoute('GET', '/articles/{id:\d+}[/{title}]', 'get_article_handler');
});
// OR you can cache the generated routing data and construct the dispatcher from the cached information
/*
 $dispatcher = FastRoute\cachedDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/user/{name}/{id:[0-9]+}', 'handler0');
    $r->addRoute('GET', '/user/{id:[0-9]+}', 'handler1');
    $r->addRoute('GET', '/user/{name}', 'handler2');
}, [
    'cacheFile' => __DIR__ . '/route.cache', // required
    'cacheDisabled' => IS_DEBUG_ENABLED,     // optional, enabled by default
]);
*/

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
$txt = 'httpMethod ('.$httpMethod.') URI ('.$uri.')'.PHP_EOL;fwrite($myfile, $txt);
// Strip query string (?foo=bar) and decode URI
if ( false !== $pos = strpos($uri, '?') ) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);
$txt = 'uri ('.$uri.')'.PHP_EOL;fwrite($myfile, $txt);
if ( isset( $_SERVER['POST'] ) && !empty( $_SERVER['POST'] ) )
{
    $txt = 'post'.PHP_EOL;fwrite($myfile, $txt);
    fwrite($myfile, print_r($_SERVER['POST'], TRUE));

}
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
$txt = 'routeInfo :'.PHP_EOL;fwrite($myfile, $txt);
fwrite($myfile, print_r($routeInfo, TRUE));
//$txt = 'not found('.FastRoute\Dispatcher::NOT_FOUND.') not allowed ('.FastRoute\Dispatcher::METHOD_NOT_ALLOWED.') found ('.FastRoute\Dispatcher::FOUND.')'.PHP_EOL;fwrite($myfile, $txt);

switch ( $routeInfo[0] ) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
$txt = 'Route not found'.PHP_EOL;fwrite($myfile, $txt);
fclose($myfile);
        //echo $twig->render('web/'.$session->config['website_skin'].'/errorPages/error404.html.twig', array( 'lang' => $lang ));
        require_once APP_ROOT_PATH.'/src/controller/baseViewController.php';
        require_once APP_ROOT_PATH.'/src/controller/web/errorViewController.php';
        $class_to_load = 'src\\controller\\web\\errorViewController';
//if ( $_ENV['env_env'] == 'dev' ) $txt = 'class to load: '.$class_to_load.PHP_EOL;fwrite($myfile, $txt);
        //echo $class_to_load;exit();
        $class = new $class_to_load( array( 'env' => $dotenv,
                                            'logger' => $logger,
                                            'logger_err' => $logger_err,
                                            'startup' => $startup,
                                            'db' => $db,
                                            'utils' => $utils,
                                            'session' => $session,
                                            'lang' => $lang,
                                            'twig' => $twig
            )
        );
        http_response_code(404);
        echo $class->error404Action();
        //header("Location: /error404");
        exit();
        //break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        // ... 405 Method Not Allowed
$txt = 'Route not allowed'.PHP_EOL;fwrite($myfile, $txt);
fclose($myfile);
        //$allowedMethods = $routeInfo[1];
        //echo $twig->render('web/'.$session->config['website_skin'].'/errorPages/error405.html.twig', array( 'lang' => $lang ));
        require_once APP_ROOT_PATH.'/src/controller/baseViewController.php';
        require_once APP_ROOT_PATH.'/src/controller/web/errorViewController.php';
        $class_to_load = 'src\\controller\\web\\errorViewController';
//$txt = 'class to load: '.$class_to_load.PHP_EOL;fwrite($myfile, $txt);
        //echo $class_to_load;exit();
        $class = new $class_to_load( array( 'env' => $dotenv,
                                            'logger' => $logger,
                                            'logger_err' => $logger_err,
                                            'startup' => $startup,
                                            'db' => $db,
                                            'utils' => $utils,
                                            'session' => $session,
                                            'lang' => $lang,
                                            'twig' => $twig
            )
        );
        http_response_code(405);
        echo $class->error405Action();
        //header("Location: /error405");
        exit();
        //break;
    case FastRoute\Dispatcher::FOUND:
$txt = 'Route found'.PHP_EOL;fwrite($myfile, $txt);
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
//$txt = PHP_EOL.'vars: '.PHP_EOL;fwrite($myfile, $txt);
//fwrite($myfile, print_r($vars, TRUE));

        $uri = $_SERVER['REQUEST_URI'];
        if ( str_contains( $uri, '?') && !str_contains( $uri, 'cron_image.img') )
        {
$txt = 'Route has URI'.PHP_EOL;fwrite($myfile, $txt);
$txt = 'URI 1: '.$uri.PHP_EOL;fwrite($myfile, $txt);
            $uri = explode('?', $uri);
$txt = 'URI 2 ========>'.PHP_EOL; fwrite($myfile, $txt);
fwrite($myfile, print_r($uri, TRUE));
            if ( isset( $uri[1] ) && !empty( $uri[1] ) )
            {
                $uri_items = explode( '&', $uri[1] );
$txt = 'URI 3 ========>'.PHP_EOL; fwrite($myfile, $txt);
fwrite($myfile, print_r($uri_items, TRUE));
                $uri = array();
                foreach ( $uri_items as $uri_key => $uri_value )
                {
$txt = 'URI 4 ========> Key '.$uri_key.PHP_EOL; fwrite($myfile, $txt);
$txt = 'URI 4 ========> value '.$uri_value.PHP_EOL; fwrite($myfile, $txt);
                    $uri_item = explode( '=', $uri_value );
                    if ( isset( $uri_item[0] ) && isset( $uri_item[1] ) )
                    {
                        if ( !empty( $uri_item[0] ) ) $uri[$uri_item[0]] = $uri_item[1];
                    }
                    else
                    {
$txt = 'Error on URI ========> '.$uri_key.' -> '.$uri_value.PHP_EOL; fwrite($myfile, $txt);
                    }
                }
            }
            else
            {
                $uri = array();
            }
$txt = 'URI 4 ========>' . PHP_EOL . PHP_EOL; fwrite($myfile, $txt);
fwrite($myfile, print_r($uri, TRUE));
        }
        else
        {
$txt = 'Route has NO URI'.PHP_EOL;fwrite($myfile, $txt);
            $uri = array();
        }

//if ( substr( $handler, 0, 14) != 'cronController' ) {
//    if ( $_ENV['env_env'] == 'dev' ) $txt = 'Route found and allowed -> '.$handler.PHP_EOL;fwrite($myfile, $txt);
//}

        $route = explode ( ':' , $handler );
        require_once APP_ROOT_PATH.'/src/controller/baseViewController.php';
        require_once APP_ROOT_PATH.'/src/controller/baseController.php';
//$txt = 'class required: ('.APP_ROOT_PATH.'/src/controller/' . $route[0] . '.php)'.PHP_EOL;fwrite($myfile, $txt);
        require_once APP_ROOT_PATH.'/src/controller/' . $route[0] . '.php';
        $class_to_load = 'src\\controller\\' . str_replace('/', '\\', $route[0]);
//$txt = 'class to load: '.$class_to_load.PHP_EOL;fwrite($myfile, $txt);
        //echo $class_to_load;exit();
        $class = new $class_to_load( array( 'env' => $dotenv,
                                            'logger' => $logger,
                                            'logger_err' => $logger_err,
                                            'startup' => $startup,
                                            'db' => $db,
                                            'utils' => $utils,
                                            'session' => $session,
                                            'lang' => $lang,
                                            'twig' => $twig,
                                            'uri' => $uri
                                            )
        );
        $method = $route[1];
//$txt = 'method to run: '.$method.PHP_EOL;fwrite($myfile, $txt);

$txt = '====================== app end ======================'.PHP_EOL;fwrite($myfile, $txt);
fclose($myfile);
        echo $class->$method( $vars );
        break;
}
