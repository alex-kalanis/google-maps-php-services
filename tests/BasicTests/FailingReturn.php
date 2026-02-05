<?php

namespace tests\BasicTests;


use kalanis\google_maps\Services;


class FailingReturn extends Services\AbstractService
{
    public function dummy(): string
    {
        return 'this is not a request';
    }
}
