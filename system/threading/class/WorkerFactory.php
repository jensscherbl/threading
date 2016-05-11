<?php
namespace Threading;

interface WorkerFactory
{
    /**
     * @return Worker
     */
    public function create(): Worker;
}
