<?php

$filename = __DIR__.preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']);
if (php_sapi_name() === 'cli-server' && is_file($filename)) {
    return false;
}

require_once __DIR__ . '/../vendor/autoload.php';

use Silex\Application;
use Silex\Provider;

Dotenv::load(__DIR__ . '/../');

$mySqlOptions = [
    'driver' => 'pdo_mysql',
    'host' => getenv('DB_HOSTNAME'),
    'dbname' => getenv('DB_DBNAME'),
    'user' => getenv('DB_USERNAME'),
    'password' => getenv('DB_PASSWORD'),
];

$sqliteOptions = [
    'driver' => 'pdo_sqlite',
    'path' => 'data/mentoring.db',
];

$dbOptions = (getenv('DB_DRIVER') == 'pdo_mysql' ? $mySqlOptions : $sqliteOptions);

$app = new Application();
$app['debug'] = true;

$app->register(new Provider\SessionServiceProvider());
$app->register(new Provider\ServiceControllerServiceProvider());
$app->register(new Provider\UrlGeneratorServiceProvider());

$app->register(new Provider\SwiftmailerServiceProvider(), [
    'swiftmailer.options' => [
        'host' => getenv('MAIL_HOST') ?: 'localhost',
        'port' => getenv('MAIL_PORT') ?: 25,
        'username' => getenv('MAIL_USERNAME') ?: '',
        'password' => getenv('MAIL_PASSWORD') ?: '',
    ],
    'swiftmailer.use_spool' => false
]);

$app->register(new Mentoring\ServiceProvider\MailerServiceProvider());

$app->register(new Provider\FormServiceProvider());
$app['form.type.extensions'] = $app->share($app->extend('form.type.extensions', function ($extensions) use ($app) {
    $extensions[] = new \Mentoring\Form\Extension\MentoringFormTypeExtension();

    return $extensions;
}));

$app->register(new Provider\ValidatorServiceProvider());
$app->register(new Provider\TranslationServiceProvider(), [
    'locale_fallbacks' => array('en')
]);

$app->register(new Silex\Provider\TwigServiceProvider(), [
    'twig.path' => [__DIR__ . '/../source/_views', __DIR__ . '/../source/_layouts'],
    'twig.form.templates' => ['form/fields.twig']
]);

$app->register(new Silex\Provider\MonologServiceProvider(), [
    'monolog.logfile' => __DIR__ . '/../data/logs/development.log',
]);

$app->register(new Silex\Provider\DoctrineServiceProvider(), [
    'db.options' => $dbOptions
]);

$taxonomyServiceProvider = new \Mentoring\ServiceProvider\TaxonomyServiceProvider();
$app->register($taxonomyServiceProvider);

$authServiceProvider = new \Mentoring\ServiceProvider\AuthServiceProvider();
$app->register($authServiceProvider);
$app->mount('/auth', $authServiceProvider);

$accountServiceProvider = new \Mentoring\ServiceProvider\AccountServiceProvider();
$app->register($accountServiceProvider);
$app->mount('/account', $accountServiceProvider);

$apiServiceProvider = new \Mentoring\ServiceProvider\ApiServiceProvider();
$app->register($apiServiceProvider);
$app->mount('/api/v0', $apiServiceProvider);

$indexServiceProvider = new \Mentoring\ServiceProvider\IndexServiceProvider();
$app->register($indexServiceProvider);
$app->mount('/', $indexServiceProvider);

$conversationServiceProvider = new \Mentoring\ServiceProvider\ConversationServiceProvider();
$app->register($conversationServiceProvider);
$app->mount('/conversations', $conversationServiceProvider);

$app->run();