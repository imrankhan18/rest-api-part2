<?php
// echo "hyy frontend";
// die;
$_SERVER['REQUEST_URI']= str_replace("/frontend/", "/", $_SERVER['REQUEST_URI']);
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Config;
use Phalcon\Loader;
use Phalcon\Url;

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

$config = new Config([]);


require_once "../../vendor/autoload.php";
$loader = new Loader();
$loader->registerDirs(
    [
        BASE_PATH . "/controllers/",
        BASE_PATH. "/models/",
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

try {
    //Handle the request
    $response = $application->handle(
        $_SERVER["REQUEST_URI"]
    );

    $response->send();
} catch (\Exception $e) {
    echo 'Exception: ', $e->getMessage();
    
}
