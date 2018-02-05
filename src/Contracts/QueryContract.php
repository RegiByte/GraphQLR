<?php

namespace BRKsReginaldo\GraphQLR\Contracts;

use Illuminate\Database\Eloquent\Model;

/**
 * Interface QueryContract
 * @package BRKsReginaldo\GraphQLR\Contracts
 */
interface QueryContract
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
    public function customType();

    /**
     * @return array
     */
    public function customTypes(): array;

    /**
     * @return array
     */
    public function customNames(): array;

    /**
     * @return array
     */
    public function customArgs(): array;
}