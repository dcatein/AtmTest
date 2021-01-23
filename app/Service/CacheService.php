<?php

namespace App\Service;

use Illuminate\Support\Facades\Redis;

class CacheService {

    /**
     * @var Redis
     */
    protected $cache;

    public function __construct(Redis $cache) {
        $this->cache = $cache;
    }

    public function setCache($key, $value){
        $this->cache::set($key, $value);
    }

    public function getCache($key){
        return $this->cache::get($key);
    }

    public function clearCache($key){
        $this->cache::command('DEL', [$key]);
    }

    public function validateCache($key)
    {
        $redisValue = $this->getCache($key);

        if($redisValue){
            return false;
        }

        $this->setCache($key, true);
        
        return true;
    }
}