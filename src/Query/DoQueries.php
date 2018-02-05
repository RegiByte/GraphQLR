<?php

namespace BRKsReginaldo\GraphQLR\Query;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use BRKsReginaldo\GraphQLR\BaseQuery;

/**
 * Trait DoQueries
 * @package BRKsReginaldo\GraphQLR\Query
 */
trait DoQueries
{
    /**
     * @var Builder
     */
    protected $query;

    /**
     * @param mixed $query
     * @return BaseQuery
     */
    public function setQuery($query)
    {
        $this->query = $query;
        return $this;
    }

    /**
     * @return Model
     */
    public function getModelInstance(): Model
    {
        return resolve($this->getModel());
    }

    /**
     * @return mixed
     */
    public function getQuery()
    {
        return is_null($this->query) ? resolve($this->getModel())->newQuery() : $this->query;
    }

    /**
     * @return $this
     */
    public function setWheres()
    {
        $args = $this->getResolveArgs();
        $excludes = collect($this->getExcludes());
        collect($this->args())
            ->reject(function ($config, $arg) use ($excludes) {
                return $excludes->contains($arg);
            })
            ->each(function ($config, $arg) use ($args) {
                if (isset($args[$arg])) {
                    $this->resolveWhere($arg, $args[$arg]);
                }
            });

        return $this;
    }

    /**
     * @param $arg
     * @param $value
     */
    public function resolveWhere($arg, $value)
    {
        if ($arg != 'id' && preg_match('/^\[(like|not\slike|\<|\>|\!\=|\<\>)\]\s([a-zA-Z0-9-_% ]+)/i', $value, $matches)) {
            $this->query->where($arg, $matches[1], $matches[2]);
        } else {
            $this->query->where($arg, $value);
        }
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getOrPaginate()
    {
        $this->query->select($this->getResolveFields());

        \Log::info($this->query->toSql());

        return $this->query->get($this->getResolveFields());
    }
}
