<?php

namespace BRKsReginaldo\GraphQLR;

use Folklore\GraphQL\Support\Type as GraphQLType;
use GraphQL\Type\Definition\Type;
use Illuminate\Database\Eloquent\Model;
use BRKsReginaldo\GraphQLR\Contracts\TypeContract;
use BRKsReginaldo\GraphQLR\Type\CustomDescription;
use BRKsReginaldo\GraphQLR\Type\CustomTypes;
use BRKsReginaldo\GraphQLR\Type\FieldFormater;
use BRKsReginaldo\GraphQLR\Type\RelationshipResolver;

/**
 * Class BaseType
 * @package BRKsReginaldo\GraphQLR
 */
abstract class BaseType extends GraphQLType implements TypeContract
{
    use FieldFormater, RelationshipResolver, CustomTypes, CustomDescription;

    /**
     * @return array
     */
    public function fields()
    {
        return $this->setCustomFields(
            collect($this->getModelInstance()->getFillable())
        )
            ->addCustomFields()
            ->formatFields()
            ->addId()
            ->addCustomType()
            ->addRelationships()
            ->addCustomDescription()
            ->getCustomFields()
            ->sortBy(function ($field, $key) {
                return $key;
            })
            ->toArray();
    }

    /**
     * @return Model;
     */
    public function getModelInstance(): Model
    {
        return resolve($this->getModel());
    }
}