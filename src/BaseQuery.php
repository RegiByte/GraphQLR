<?php

namespace BRKsReginaldo\GraphQLR;

use Folklore\GraphQL\Support\Query;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Collection;
use BRKsReginaldo\GraphQLR\Contracts\QueryContract;
use BRKsReginaldo\GraphQLR\Query\CustomAppends;
use BRKsReginaldo\GraphQLR\Query\CustomArgs;
use BRKsReginaldo\GraphQLR\Query\DoQueries;
use BRKsReginaldo\GraphQLR\Query\RelationshipFields;
use BRKsReginaldo\GraphQLR\Query\ResolveArgs;

/**
 * Class BaseQuery
 * @package BRKsReginaldo\GraphQLR
 */
abstract class BaseQuery extends Query implements QueryContract
{
    use CustomArgs, RelationshipFields, ResolveArgs, DoQueries, CustomAppends;

    /**
     * @var
     */
    protected $modelName;

    /**
     * @return $this
     */
    public function addId()
    {
        $model = $this->modelName ?? shortClass($this->getModel());

        $this->customArgs->put('id', [
            'name' => 'id',
            'type' => Type::id(),
            'description' => 'O id do model ' . $model
        ]);

        return $this;
    }

    /**
     * @param $root
     * @param $args
     * @param $context
     * @param ResolveInfo $info
     * @return Collection
     */
    public function resolve($root, $args, $context, ResolveInfo $info)
    {
        return $this
            ->setResolveArgs($args)
            ->setResolveInfo($info)
            ->setQuery($this->getQuery())
            ->setWheres()
            ->addRelationships()
            ->addOrder()
            ->getOrPaginate();
    }


}