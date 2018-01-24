<?php


namespace BRKsReginaldo\GraphQLR\Contracts;


use Illuminate\Database\Eloquent\Model;

/**
 * Interface TypeContract
 * @package BRKsReginaldo\GraphQLR\Contracts
 */
interface TypeContract
{
    /**
     * @return string
     */
    public function getModel(): string;

    /**
     * @return Model
     */
    public function getModelInstance(): Model;

    /**
     * @return array
     */
    public function customTypes(): array;

    /**
     * @return array
     */
    public function customDescriptions(): array;

    /**
     * @return array
     */
    public function customFields(): array;
}