<?php

namespace tests\ServiceTests;


use kalanis\google_maps\Services;


class XFactory extends Services\ServiceFactory
{
    protected array $serviceMethodMap = [
        'directions' => Services\Directions::class,
        'unusable' => XClass::class,
    ];
}
