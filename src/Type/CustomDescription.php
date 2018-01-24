<?php

namespace BRKsReginaldo\GraphQLR\Type;

use BRKsReginaldo\GraphQLR\BaseType;

/**
 * Trait CustomDescription
 * @package BRKsReginaldo\GraphQLR\Type
 */
trait CustomDescription
{

    /**
     * @param $field
     * @return bool
     */
    public function hasCustomDescription($field)
    {
        return collect($this->customDescriptions())->has($field);
    }

    /**
     * @return BaseType
     */
    public function addCustomDescription()
    {
        $this->setCustomFields(
            $this->getCustomFields()
                ->map(function ($value, $attribute) {
                    $value['description'] = $this->hasCustomDescription($attribute) ? $this->getCustomDescription($attribute) : $value['description'];
                    return $value;
                })
        );

        return $this;
    }

    /**
     * @param $attribute
     * @return mixed
     */
    public function getCustomDescription($attribute)
    {
        return collect($this->customDescriptions())->get($attribute);
    }
}