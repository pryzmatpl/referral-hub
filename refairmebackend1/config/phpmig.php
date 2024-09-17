<?php

use Phpmig\Adapter;

$container = new ArrayObject();

$container['db'] = new PDO('mysql:dbname=prism;host=localhost', 'root', '1tJ3nBCxy');
$container['phpmig.adapter'] = new Adapter\PDO\Sql($container['db'], 'migrations');
$container['phpmig.migrations_path'] = __DIR__ . DIRECTORY_SEPARATOR . 'migrations';

return $container;
