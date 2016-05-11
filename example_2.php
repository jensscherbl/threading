<?php
namespace Example;

use \Threading\Fhreads\WorkerFactory;

require 'vendor/autoload.php';

$worker = (new Workerfactory)->create();
$worker->start();

foreach (range(1,999) as $index) {

    usleep(rand(0,9999));

    // assign task

    $worker->push(function () use ($index) {

        usleep(rand(0,9999));

        echo 'Task ' . $index . '.' . PHP_EOL;
    });
}

echo 'All assigned.' . PHP_EOL;

$worker->stop();
