<?php namespace AjhDBCache\Impl\Cache;

use Predis\Client as Redis;
use AjhDBCache\Contracts\Cache\Cache;

class RedisCache implements Cache
{

    /**
     * @var Redis
     */
    private $redis;

    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }

    public function set($keyValues)
    {
        $pipe = $this->redis->pipeline();

        foreach ($keyValues as $key => $value) {
            if (!is_null($value)) {
                $value = json_encode($value);
                $pipe->set($key, $value, 'EX', 86400, 'NX');
            }
        }
    }

    public function get($keys)
    {
        if (!$keys) {
            return [];
        }

        $keyValue = array_combine($keys, $this->redis->mget($keys));
        array_walk($keyValue, function (&$item) {
            $item = json_decode($item);
        });
        $keyValue = array_filter($keyValue, function ($value) {
            return !is_null($value);
        });

        return $keyValue;
    }

    public function del($keys)
    {
        return $this->redis->del($keys);
    }
}