<?php

if (php_sapi_name() === 'cli-server') {
    $filename = __DIR__.preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']);
    if(is_file($filename)) {
        return false;
    }
}

require_once __DIR__ . '/../vendor/autoload.php';

use Silex\Application;
use Silex\Provider;

$app = new Application();
$app['debug'] = true;

$app->register(new \Rpodwika\Silex\YamlConfigServiceProvider(__DIR__ . '/../app/config/parameters.yml'));
$app->register(new Provider\SessionServiceProvider());
$app->register(new Provider\ServiceControllerServiceProvider());
$app->register(new Provider\RoutingServiceProvider());

$app->register(new Provider\SwiftmailerServiceProvider(), [
    'swiftmailer.options' => $app['config']['mail'],
    'swiftmailer.use_spool' => false
]);

$app->register(new \Mentoring\Mailer\ServiceProvider\MailerServiceProvider());

$app->register(new Provider\FormServiceProvider());
$app['form.type.extensions'] = $app->extend('form.type.extensions', function ($extensions) use ($app) {
    $extensions[] = new \Mentoring\Account\Form\Extension\MentoringFormTypeExtension();

    return $extensions;
});

$app->register(new Provider\ValidatorServiceProvider());
$app->register(new Provider\TranslationServiceProvider(), [
    'locale' => 'en'
]);

$app->register(new Silex\Provider\TwigServiceProvider(), [
    'twig.path' => __DIR__ . '/../views',
    'twig.form.templates' => ['form/fields.twig']
]);

$app->register(new Silex\Provider\MonologServiceProvider(), [
    'monolog.logfile' => __DIR__ . '/../data/logs/development.log',
]);

$app->register(new Silex\Provider\DoctrineServiceProvider(), [
    'db.options' => $app['config']['database'],
]);

$app->register(new \Knp\Provider\ConsoleServiceProvider(), [
    'console.name' => 'PHPMentoring Admin Console',
    'console.version' => '0.0.1',
    'console.project_directory' => __DIR__,
]);

$taxonomyServiceProvider = new \Mentoring\Taxonomy\ServiceProvider\TaxonomyServiceProvider();
$app->register($taxonomyServiceProvider);

$authServiceProvider = new \Mentoring\Auth\ServiceProvider\AuthServiceProvider();
$app->register($authServiceProvider);
$app->mount('/auth', $authServiceProvider);

$accountServiceProvider = new \Mentoring\Account\ServiceProvider\AccountServiceProvider();
$app->register($accountServiceProvider);
$app->mount('/account', $accountServiceProvider);

$apiServiceProvider = new \Mentoring\PublicSite\ServiceProvider\ApiServiceProvider();
$app->register($apiServiceProvider);
$app->mount('/api/v0', $apiServiceProvider);

$indexServiceProvider = new \Mentoring\PublicSite\ServiceProvider\IndexServiceProvider();
$app->register($indexServiceProvider, [
    'publicsite.blog_directory' => getenv('BLOG_DIRECTORY'),
]);
$app->mount('/', $indexServiceProvider);

$conversationServiceProvider = new \Mentoring\Conversation\ServiceProvider\ConversationServiceProvider();
$app->register($conversationServiceProvider);
$app->mount('/conversations', $conversationServiceProvider);

if (php_sapi_name() === 'cli') {
    $app['console']->run();
} else {
    $app->run();
}