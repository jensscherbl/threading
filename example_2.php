<?php
namespace Example;

use \Threading\Fhreads\QueueState;
use \Threading\Fhreads\Thread;

use \Threading\QueueStore;
use \Threading\TaskQueue;
use \Threading\Worker;

require 'vendor/autoload.php';

$worker = new Worker(
    new Thread,
    new TaskQueue(
        new QueueState,
        new QueueStore
    )
);
$worker->start();

foreach (range(1,999) as $index) {

    usleep(rand(0,9999));

    // assign task

    $worker->push(function () use ($index) {

        usleep(rand(0,9999));

        echo 'Task ' . $index . '.' . PHP_EOL;
    });

    echo 'Task ' . $index . ' assigned.' . PHP_EOL;
}

echo 'All assigned.' . PHP_EOL;

$worker->stop();
