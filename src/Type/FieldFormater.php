<?php

namespace BRKsReginaldo\GraphQLR\Type;


use GraphQL\Type\Definition\Type;
use Illuminate\Support\Collection;
use BRKsReginaldo\GraphQLR\BaseType;

/**
 * Trait FieldFormater
 * @package BRKsReginaldo\GraphQLR\Type
 */
trait FieldFormater
{
    /**
     * @var Collection
     */
    protected $customFields;

    /**
     * @return Collection
     */
    public function getCustomFields()
    {
        return $this->customFields;
    }

    /**
     * @param mixed $fields
     * @return BaseType
     */
    public function setCustomFields($fields)
    {
        $this->customFields = $fields;
        return $this;
    }

    /**
     * @return BaseType
     */
    public function addCustomFields()
    {
        collect($this->customFields())
            ->each(function ($field) {
                $this->customFields->push($field);
            });
        return $this;
    }

    /**
     * @return BaseType
     */
    public function formatFields()
    {
        $this->setCustomFields(
            $this->getCustomFields()
                ->keyBy(function ($attribute) {
                    return $attribute;
                })
                ->map(function ($attribute) {
                    $model = basename($this->getModel());
                    return [
                        'type' => Type::string(),
                        'description' => "The $attribute field of the $model"
                    ];
                })
        );
        return $this;
    }

    /**
     * @return BaseType
     */
    public function addId()
    {
        $model = basename($this->getModel());
        $this->customFields->put('id', [
            'type' => Type::id(),
            'description' => "The id of the $model"
        ]);

        return $this;
    }
}