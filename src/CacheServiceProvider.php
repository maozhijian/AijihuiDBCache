<?php

namespace AjhDBCache;


use AjhDBCache\Contracts\Cache\Cache;
use AjhDBCache\Contracts\Cache\Meta;
use AjhDBCache\Impl\Cache\RedisCache;
use AjhDBCache\Impl\Cache\RedisMeta;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class CacheServiceProvider extends ServiceProvider
{
    public function boot(){
        $loader = AliasLoader::getInstance();
        $loader->alias('Schema', SchemaFacade::class);
    }

    public function register()
    {
        $this->app->singleton(Cache::class, function () {
            return new RedisCache($this->getPredis());
        });
        $this->app->alias(Cache::class, 'db_cache');

        $this->app->singleton(Meta::class, function () {
            return new RedisMeta($this->getPredis());
        });
    }

    public function getPredis()
    {
        $redis = $this->app->make('redis');
        return $redis->connection();
    }
}