<?php namespace AjhDBCache;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Query\Builder;

abstract class Model extends EloquentModel
{
    protected static $disableReadCache = false;
    protected $needCache = true;

    public function needCache()
    {
        return $this->needCache && !self::$disableReadCache;
    }

    public function primaryKey()
    {
        return $this->primaryKey;
    }

    public function table()
    {
        return $this->table;
    }


    protected function newBaseQueryBuilder()
    {
        $conn = $this->getConnection();
        $grammar = $conn->getQueryGrammar();
        $queryBuilder = new QueryBuilder(
            $conn, $grammar, $conn->getPostProcessor());
        $queryBuilder->setModel($this);
        return $queryBuilder;
    }

    /**
     * 判断更新数据库的时候是否需要更新缓存
     */
    public function needFlushCache()
    {
        return $this->needCache;
    }

    public function newEloquentBuilder($query)
    {
        $builder = new \Illuminate\Database\Eloquent\Builder($query);

        $builder->macro('key', function (\Illuminate\Database\Eloquent\Builder $builder) {
            return $builder->getQuery()->key();
        });

        $builder->macro('flush', function (\Illuminate\Database\Eloquent\Builder $builder) {
            return $builder->getQuery()->flush();
        });

        return $builder;
    }

    /**
     * 关闭查询数据库的时候读取缓存的逻辑（更新数据库的时候还会更新缓存）
     */
    public static function disableReadCache()
    {
        self::$disableReadCache = true;
    }

}