<?php
namespace Threading;

interface PoolFactory
{
    /**
     * @param int $size
     *
     * @return Pool
     */
    public function create(int $size): Pool;
}
