<?php

namespace BRKsReginaldo\GraphQLR\Query;


use GraphQL\Type\Definition\ResolveInfo;
use BRKsReginaldo\GraphQLR\BaseQuery;

/**
 * Trait ResolveArgs
 * @package BRKsReginaldo\GraphQLR\Query
 */
trait ResolveArgs
{

    /**
     * @var ResolveInfo
     */
    protected $resolveInfo;
    /**
     * @var
     */
    protected $resolveArgs;

    /**
     * @return mixed
     */
    public function getResolveArgs()
    {
        return $this->resolveArgs;
    }

    /**
     * @return mixed
     */
    public function getResolveFields()
    {
        $attributes = collect($this->getModelInstance()->getFillable());

        $fieldsSelection = collect($this->resolveInfo->getFieldSelection(0))->keys()
            ->filter(function ($field) use ($attributes) {
                return $attributes->contains($field) || count($attributes->filter(function ($attribute) use ($field) {
                        $singular = str_singular($field);
                        return preg_match("/$singular/", $attribute);
                    }));
            })
            ->map(function ($field) use ($attributes) {
                $query = $attributes->filter(function ($attribute) use ($field) {
                    $singular = str_singular($field);
                    return preg_match("/$singular/", $attribute);
                });

                if (count($query)) {
                    return $query->first();
                }

                return $field;
            });

        $fieldsSelection->push('id');

        collect($this->customAppends())
            ->each(function ($attribute) use ($fieldsSelection) {
                $fieldsSelection->push($attribute);
            });

        return $fieldsSelection->unique()->toArray();
    }

    /**
     * @param mixed $setResolveArgs
     * @return BaseQuery
     */
    public function setResolveArgs($setResolveArgs)
    {
        $this->resolveArgs = $setResolveArgs;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getResolveInfo()
    {
        return $this->resolveInfo;
    }

    /**
     * @param mixed $setResolveInfo
     * @return BaseQuery
     */
    public function setResolveInfo($setResolveInfo)
    {
        $this->resolveInfo = $setResolveInfo;
        return $this;
    }
}