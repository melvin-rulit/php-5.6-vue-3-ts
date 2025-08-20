<?php

namespace App\Services;

use RedisException;

class RedisClient
{
    private $redis;

    /**
     * @throws RedisException
     */
    public function __construct()
    {
        $this->redis = new \Redis();
        $this->redis->connect('redis');
    }

    /**
     * @throws RedisException
     */
    public function set($key, $value, $ttl = 3600)
    {
        $this->redis->set($key, $value, $ttl);
    }

    /**
     * @throws RedisException
     */
    public function get($key)
    {
        return $this->redis->get($key);
    }

    /**
     * @throws RedisException
     */
    public function del($key)
    {
        $this->redis->del($key);
    }
}