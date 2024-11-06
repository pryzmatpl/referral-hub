<?php
use Pimple\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Phpmig\Adapter\Illuminate\Database;

$container = new Container();
$dbName = env('DB_DATABASE');
$dbHost = env('DB_HOST');
$dbUser = env('DB_USERNAME');
$dbPassword = env('DB_PASSWORD');
$database = 'prism';

$container['config'] = [
    'driver' => 'mysql',
    'host' => $dbHost,
    'database' => $database,
    'username' => $dbUser,
    'password' => $dbPassword,
    'prefix' => '',
//  'charset' => 'xxx',
//  'collation' => 'xxx',
];

$container['db'] = function ($c) {
    $capsule = new Capsule();
    $capsule->addConnection($c['config']);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    return $capsule;
};

$container['phpmig.adapter'] = function ($c) {
    return new Database($c['db'], 'migrations');
};

$container['phpmig.migrations_path'] = __DIR__ . DIRECTORY_SEPARATOR . 'migrations';

return $container;