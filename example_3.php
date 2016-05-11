<?php
namespace Example;

use \Threading\Fhreads\PoolFactory;

require 'vendor/autoload.php';

$pool = (new PoolFactory)->create(8);
$pool->start();

foreach (range(1,999) as $index) {

    usleep(rand(0,9999));

    // assign task

    $pool->push(function () use ($index) {

        usleep(rand(0,9999));

        echo 'Task ' . $index . '.' . PHP_EOL;
    });
}

echo 'All assigned.' . PHP_EOL;

$pool->stop();
