<?php

namespace BRKsReginaldo\GraphQLR\Type;


use GraphQL\Type\Definition\Type;
use BRKsReginaldo\GraphQLR\BaseType;

/**
 * Trait RelationshipResolver
 * @package BRKsReginaldo\GraphQLR\Type
 */
trait RelationshipResolver
{
    /**
     * @param $relationship
     * @return string
     */
    public function getRelationshipName($relationship): string
    {
        return str_singular(title_case($relationship));
    }

    /**
     * @param $relationship
     * @return string
     */
    public function getCorrectRelationshipName($relationship)
    {
        $payload = str_singular(title_case($relationship));

        $classNamespace = explode('\\', $this->getModel());
        $className = array_pop($classNamespace);

        $payload = "$className $payload";

        return studly_case($payload);
    }

    /**
     * @param $relationship
     * @return mixed
     */
    public function existingRelationship($relationship)
    {
        $types = collect(config('graphql.types'))->keys();

        return $types->first(function($type) use ($relationship) {
            return $type === $this->getRelationshipName($relationship) ||
               $type === $this->getCorrectRelationshipName($relationship);
        });
    }

    /**
     * @return BaseType
     */
    public function addRelationships()
    {
        $types = collect(config('graphql.types'))->keys();

        collect($this->getModelInstance()->relationships())
            ->filter(function ($info, $relationship) use ($types) {
                return count($types->filter(function ($type) use ($relationship) {
                        return $type === $this->getRelationshipName($relationship) ||
                            $type === $this->getCorrectRelationshipName($relationship);
                    })) > 0;
            })
            ->filter(function ($info, $relationship) {
		$namespace = str_replace('\\', '', app()->getNamespace());
                return preg_match("/^(Modules|$namespace)/", $info['model']);
            })
            ->map(function ($info, $relationship) {
                $originalInfo = $info;
                $relationship = $this->existingRelationship($relationship);
                unset($info['model']);
                $model = basename($this->getModel());
                $info['type'] = \GraphQL::type($relationship);
                $info['description'] = "The $relationship relationship of the $model";

                if (preg_match("/(many)/i", $originalInfo['type'])) {
                    $info['type'] = Type::listOf($info['type']);
                }

                return $info;
            })
            ->each(function ($info, $relationship) {
                $this->customFields->put($relationship, $info);
            });

        return $this;
    }
}
