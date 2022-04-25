<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Config;
use Phalcon\Loader;
use Phalcon\Url;
use Phalcon\Events\Manager as EventsManager;
use App\Listener\Webhooks;

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH);
// echo APP_PATH;
// die;
$config = new Config([]);

require_once "../../vendor/autoload.php";
$loader = new Loader();
$loader->registerDirs(
    [
        BASE_PATH . "/controllers/",
        BASE_PATH. "/models/",
        BASE_PATH. "/webhooks/",
    ]
);
$loader->registerNamespaces(
    [

        'App\Listener' => APP_PATH . "/listener/",
    ]
);
$loader->register();
$container = new FactoryDefault();
$application = new Application($container);

$container->set(
    'mongo',
    function () {
        $mongo = new \MongoDB\Client("mongodb://mongo", array("username" => 'root', "password" => "password123"));

        return $mongo;
    },
    true
);
$application = new Application($container);

$container->set(
    'view',
    function () {
        $view = new View();
        $view->setViewsDir(BASE_PATH . '/views');
        return $view;
    }
);

$container->set(
    'url',
    function () {
        $url = new Url();
        $url->setBaseUri('/');
        return $url;
    }
);

$eventsManager = new EventsManager();
$container->set(
    'eventsManager',
    function () use ($eventsManager) {
        $eventsManager->attach(
            'webhooks',
            new Webhooks()
        );
        return $eventsManager;
    }
);

try {
    //Handle the request
    $response = $application->handle(
        $_SERVER["REQUEST_URI"]
    );

    $response->send();
} catch (\Exception $e) {
    echo 'Exception: ', $e->getMessage();
    
}
