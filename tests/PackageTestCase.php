<?php

namespace Vendor\Package\Tests;

use PHPUnit\Framework\TestCase;
use BRKsReginaldo\GraphQLR\GraphQLRServiceProvider;

abstract class PackageTestCase extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [GraphQLRServiceProvider::class];
    }
}
