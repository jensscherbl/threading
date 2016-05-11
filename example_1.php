<?php
namespace Example;

use \Threading\Fhreads\Thread;

require 'vendor/autoload.php';

$thread = new Thread;

$thread->execute(function () {

    usleep(rand(0,9999));

    echo 'Hello world.' . PHP_EOL;
});

echo 'All assigned.' . PHP_EOL;

$thread->join();
