<?php


namespace BRKsReginaldo\GraphQLR;


use Folklore\GraphQL\Support\Mutation;
use Illuminate\Database\Eloquent\Model;
use BRKsReginaldo\GraphQLR\Contracts\MutationContract;
use BRKsReginaldo\GraphQLR\Mutation\CustomArgs;

/**
 * Class BaseMutation
 * @package BRKsReginaldo\GraphQLR
 */
abstract class BaseMutation extends Mutation implements MutationContract
{
    use CustomArgs;

    /**
     * @return Model
     */
    public function getModelInstance(): Model
    {
        return resolve($this->getModel());
    }
}