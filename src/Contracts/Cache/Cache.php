<?php namespace AjhDBCache\Contracts\Cache;

/**
 * Created by PhpStorm.
 * User: maozhijian
 * Date: 17/12/19
 * Time: 下午7:13
 */
interface Cache
{
    public function set($keyValues);

    public function get($keys);

    public function del($keys);
}