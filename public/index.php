<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Silex\Application;
use Silex\Provider;

Dotenv::load(__DIR__ . '/../');

$app = new Application();
$app['debug'] = true;

$app->register(new Provider\SessionServiceProvider());
$app->register(new Provider\ServiceControllerServiceProvider());
$app->register(new Provider\UrlGeneratorServiceProvider());
$app->register(new Provider\SwiftmailerServiceProvider());
$app->register(new Provider\FormServiceProvider());
$app->register(new Provider\ValidatorServiceProvider());
$app->register(new Provider\TranslationServiceProvider(), array(
    'locale_fallbacks' => array('en')
));

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/../views',
));

$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__ . '/../data/logs/development.log',
));

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => 'pdo_mysql',
        'host' => getenv('DB_HOSTNAME'),
        'dbname' => getenv('DB_DBNAME'),
        'user' => getenv('DB_USERNAME'),
        'password' => getenv('DB_PASSWORD'),
    ),
));

$authServiceProvider = new \Mentoring\ServiceProvider\AuthServiceProvider();
$app->register($authServiceProvider);
$app->mount('/auth', $authServiceProvider);

$indexServiceProvider = new \Mentoring\ServiceProvider\IndexServiceProvider();
$app->register($indexServiceProvider);
$app->mount('/', $indexServiceProvider);

$accountServiceProvider = new \Mentoring\ServiceProvider\AccountServiceProvider();
$app->register($accountServiceProvider);
$app->mount('/account', $accountServiceProvider);

$app->run();