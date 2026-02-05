<?php

namespace tests\BasicTests;


use kalanis\google_maps\Services;


class XFactory extends Services\ServiceFactory
{
    protected array $serviceMethodMap = [
        'dummy' => FailingReturn::class,
    ];
}
