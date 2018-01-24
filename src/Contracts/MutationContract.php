<?php
namespace BRKsReginaldo\GraphQLR\Contracts;
use Illuminate\Database\Eloquent\Model;

/**
 * Interface MutationContract
 * @package BRKsReginaldo\GraphQLR\Contracts
 */
interface MutationContract
{

    /**
     * @return string
     */
    public function getModel(): string;

    public function getModelInstance(): Model;

    public function mutationFields(): array;

    /**
     * @return array
     */
    public function customArgs(): array;

    /**
     * @return array
     */
    public function customRules(): array;

    /**
     * @return array
     */
    public function customNames(): array;

    /**
     * @return array
     */
    public function customTypes(): array;

    public function generalRules(): array;

    public function generalRulesException(): array;

    /**
     * @param $root
     * @param array $args
     * @return mixed
     */
    public function resolve($root, $args = []);
}