<?php

$forbiden_uris = array ('env.bak',
                        'server.key',
                        '.venv',
                        '.env1',
                        'backend',
                        'config.php.bak',
                        'settings.ini',
                        '/passwd',
                        'servlet',
                        'public/index',
                        'upgrade',
                        'index.action',
                        'uploadfile.php',
                        'webadm',
                        'doLogin',
                        'php5',
                        'phpinfo.php ',
                        'info.php',
                        'wp-config',
                        'yaml',
                        'credentials',
                        'json',
                        '.key',
                        'dump.sh',
                        'docker',
                        'app.js',
);

foreach ( $forbiden_uris as $forbiden_uri )
{
    if ( str_contains( strtolower($_SERVER['REQUEST_URI']), $forbiden_uri ) )
    {
        echo 'Nothing here!!!';
        exit;
    }
}