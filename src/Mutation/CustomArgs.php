<?php

namespace BRKsReginaldo\GraphQLR\Mutation;


use GraphQL\Type\Definition\Type;
use Illuminate\Support\Collection;

/**
 * Trait CustomArgs
 * @package BRKsReginaldo\GraphQLR\Mutation
 */
trait CustomArgs
{
    /**
     * @var Collection
     */
    protected $customArgs;

    /**
     * @var bool
     */
    protected $hasId = false;

    /**
     * @return array
     */
    public function args()
    {
        return $this->setMutationFields()
            ->addId()
            ->addCustomArgs()
            ->formatCustomArgs()
            ->addCustomNames()
            ->addCustomTypes()
            ->addGeneralRules()
            ->addCustomRules()
            ->getCustomArgs()
            ->toArray();
    }

    /**
     * @return $this
     */
    public function setMutationFields()
    {
        if ($this->mutationFields() === ['*']) {
            $this->setCustomArgs(
                collect($this->getModelInstance()->getFillable())
            );
        } else {
            $this->setCustomArgs(
                collect($this->mutationFields())
            );
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function addId()
    {
        if ($this->hasId) {
            $this->customArgs->push('id');
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function addCustomArgs()
    {
        collect($this->customArgs())
            ->each(function ($arg) {
                $this->customArgs->push($arg);
            });

        return $this;
    }

    /**
     * @return $this
     */
    public function formatCustomArgs()
    {
        $this->setCustomArgs(
            $this->getCustomArgs()
                ->keyBy(function ($arg) {
                    return $arg;
                })
                ->map(function ($arg) {
                    return [
                        'name' => $arg,
                        'type' => Type::string(),
                        'description' => "The $arg attribute for the " . shortClass($this->getModel()) . ' model.',
                        'rules' => []
                    ];
                })
        );

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
                    if ($this->hasCustomName($arg)) {
                        return [
                            'name' => $this->getCustomName($arg),
                            'type' => $config['type'],
                            'rules' => $config['rules'],
                            'description' => $config['description']
                        ];
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
    public function hasCustomName($arg)
    {
        return (boolean)collect($this->customNames())
            ->filter(function ($config, $customArg) use ($arg) {
                return $arg === $customArg;
            })
            ->count();
    }

    /**
     * @param $arg
     * @return mixed
     */
    public function getCustomName($arg)
    {
        return collect($this->customNames())
            ->filter(function ($config, $customArg) use ($arg) {
                return $arg === $customArg;
            })
            ->first();
    }

    /**
     * @return $this
     */
    public function addCustomTypes()
    {
        $this->setCustomArgs(
            $this->getCustomArgs()
                ->map(function ($config, $arg) {
                    if ($this->hasCustomType($arg)) {
                        return [
                            'name' => $config['name'],
                            'type' => $this->getCustomType($arg),
                            'rules' => $config['rules'],
                            'description' => $config['description']
                        ];
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
    public function hasCustomType($arg)
    {
        return (boolean)collect($this->customTypes())
            ->filter(function ($config, $customArg) use ($arg) {
                return $arg === $customArg;
            })
            ->count();
    }

    /**
     * @param $arg
     * @return mixed
     */
    public function getCustomType($arg)
    {
        return collect($this->customTypes())
            ->filter(function ($config, $customArg) use ($arg) {
                return $arg === $customArg;
            })
            ->first();
    }

    /**
     * @return $this
     */
    public function addGeneralRules()
    {
        $rules = $this->generalRules();

        $this->setCustomArgs(
            $this->getCustomArgs()
                ->map(function ($config, $customArg) use ($rules) {
                    if (!$this->onExceptions($customArg)) {
                        $config['rules'] = array_merge($config['rules'], $rules);
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
    public function onExceptions($arg)
    {
        return (boolean)collect($this->generalRulesException())
            ->filter(function ($customArg) use ($arg) {
                return $arg === $customArg;
            })
            ->count();
    }

    /**
     * @return $this
     */
    public function addCustomRules()
    {
        $this->setCustomArgs(
            $this->getCustomArgs()
                ->map(function ($config, $arg) {
                    if ($this->hasCustomRules($arg)) {
                        return [
                            'name' => $config['name'],
                            'type' => $config['type'],
                            'rules' => array_unique(array_merge($config['rules'], $this->getCustomRules($arg))),
                        ];
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
    public function hasCustomRules($arg)
    {
        return (boolean)collect($this->customRules())
            ->filter(function ($config, $customArg) use ($arg) {
                return $arg === $customArg;
            })
            ->count();
    }

    /**
     * @param $arg
     * @return mixed
     */
    public function getCustomRules($arg)
    {
        return collect($this->customRules())
            ->filter(function ($config, $customArg) use ($arg) {
                return $arg === $customArg;
            })
            ->first();
    }

    /**
     * @return Collection
     */
    public function getCustomArgs()
    {
        return $this->customArgs;
    }

    /**
     * @param $customArgs
     * @return CustomArgs
     */
    public function setCustomArgs($customArgs)
    {
        $this->customArgs = $customArgs;
        return $this;
    }
}