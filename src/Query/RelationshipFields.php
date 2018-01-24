<?php

namespace BRKsReginaldo\GraphQLR\Query;


/**
 * Trait RelationshipFields
 * @package BRKsReginaldo\GraphQLR\Query
 */
trait RelationshipFields
{
    /**
     * @return $this
     */
    public function addRelationships()
    {
        $this->query->with($this->getRelationshipArgs());

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRelationshipArgs()
    {
        /** @var Collection $relationships */
        $relationships = $this->getModelInstance()->relationships();

        return collect($this->resolveInfo->getFieldSelection(0))->keys()
            ->filter(function ($field) use ($relationships) {
                return $relationships->has($field);
            })
            ->values()
            ->toArray();
    }
}