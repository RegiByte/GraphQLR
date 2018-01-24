<?php

namespace BRKsReginaldo\GraphQLR\Type;

use BRKsReginaldo\GraphQLR\BaseType;


/**
 * Trait CustomTypes
 * @package BRKsReginaldo\GraphQLR\Type
 */
trait CustomTypes
{

    /**
     * @param $field
     * @return bool
     */
    public function hasCustomType($field)
    {
        return collect($this->customTypes())->has($field);
    }

    /**
     * @return BaseType
     */
    public function addCustomType()
    {
        $this->setCustomFields(
            $this->getCustomFields()
                ->map(function ($value, $attribute) {
                    $value['type'] = $this->hasCustomType($attribute) ? $this->getCustomType($attribute) : $value['type'];
                    return $value;
                })
        );

        return $this;
    }

    /**
     * @param $attribute
     * @return mixed
     */
    protected function getCustomType($attribute)
    {
        return collect($this->customTypes())->get($attribute);
    }
}