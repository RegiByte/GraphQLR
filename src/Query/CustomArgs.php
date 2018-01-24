<?php

namespace BRKsReginaldo\GraphQLR\Query;


use GraphQL\Type\Definition\Type;
use Illuminate\Support\Collection;
use BRKsReginaldo\GraphQLR\BaseQuery;

/**
 * Trait CustomArgs
 * @package BRKsReginaldo\GraphQLR\Query
 */
trait CustomArgs
{

    /**
     * @var Collection
     */
    protected $customArgs;

    /**
     * @var array
     */
    protected $argExcludes = [
        'orderBy',
        'orderDirection'
    ];

    /**
     * @return array
     */
    public function args()
    {
        return $this->setCustomArgs($this->getModelInstance()->getFillable())
            ->addCustomArgs()
            ->formatCustomArgs()
            ->addId()
            ->addOrderArg()
            ->addCustomNames()
            ->addCustomTypes()
            ->getCustomArgs()
            ->toArray();
    }

    /**
     * @param $arg
     * @return $this
     */
    public function addArg($arg)
    {
        $this->setCustomArgs(
            array_merge(
                $this->getCustomArgs(),
                [
                    $arg
                ]
            )
        );

        return $this;
    }

    /**
     * @return $this
     */
    public function addOrderArg()
    {
        $this->customArgs->put('orderBy', [
            'name' => 'orderBy',
            'type' => Type::string(),
            'description' => 'O atributo orderBy do model ' . shortClass($this->getModel()) . '.'
        ]);
        $this->customArgs->put('orderDirection', [
            'name' => 'orderDirection',
            'type' => Type::string(),
            'description' => 'O atributo order direction do model ' . shortClass($this->getModel()) . '.'
        ]);

        return $this;
    }

    /**
     * @return $this
     */
    public function formatCustomArgs()
    {
        $model = $this->modelName ?? shortClass($this->getModel());
        $this->setCustomArgs(
            collect($this->getCustomArgs())
                ->keyBy(function ($arg) {
                    return $arg;
                })
                ->map(function ($arg) use ($model) {
                    return [
                        'name' => $arg,
                        'type' => Type::string(),
                        'description' => "O atributo $arg do model " . $model
                    ];
                })
        );

        return $this;
    }

    /**
     * @return $this
     */
    public function addCustomArgs()
    {
        collect($this->customArgs())
            ->each(function ($arg) {
                $this->addArg($arg);
            });

        return $this;
    }

    /**
     * @return $this
     */
    public function addCustomNames()
    {
        $this->setCustomArgs(
            $this->getCustomArgs()
                ->map(function ($config, $arg) {
                    if ($this->hasCustomArgName($arg)) {
                        $config['name'] = $this->getCustomArgName($arg);
                    }
                    return $config;
                })
        );

        return $this;
    }

    /**
     * @return $this
     */
    public function addCustomTypes()
    {
        $this->setCustomArgs(
            $this->getCustomArgs()
                ->map(function ($config, $arg) {
                    if ($this->hasCustomArgType($arg)) {
                        $config['type'] = $this->getCustomArgType($arg);
                    }
                    return $config;
                })
        );

        return $this;
    }

    /**
     * @param $arg
     * @return bool
     */
    public function hasCustomArgName($arg)
    {
        return collect($this->customNames())->has($arg);
    }

    /**
     * @param $arg
     * @return mixed
     */
    public function getCustomArgName($arg)
    {
        return collect($this->customNames())->get($arg);
    }

    /**
     * @param $arg
     * @return bool
     */
    public function hasCustomArgType($arg)
    {
        return collect($this->customTypes())->has($arg);
    }

    /**
     * @param $arg
     * @return mixed
     */
    public function getCustomArgType($arg)
    {
        return collect($this->customTypes())->get($arg);
    }

    /**
     * @return $this
     */
    public function addOrder()
    {
        $args = collect($this->getResolveArgs());

        //  If we have an order by attribute
        if ($args->has('orderBy')) {
            //  We set the order and direction based on it
            $this->query->orderBy($args->get('orderBy'), $args->get('orderDirection', 'asc'));
        }

        return $this;
    }

    /**
     * @param mixed $customArgs
     * @return BaseQuery
     */
    public function setCustomArgs($customArgs)
    {
        $this->customArgs = $customArgs;
        return $this;
    }

    /**
     * @return array|Collection
     */
    public function getCustomArgs()
    {
        return $this->customArgs;
    }


    /**
     * @return array
     */
    public function getExcludes()
    {
        return $this->argExcludes;
    }
}