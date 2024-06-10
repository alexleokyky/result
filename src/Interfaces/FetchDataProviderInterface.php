<?php

namespace App;

interface FetchDataProviderInterface
{
    public function getData(): array;
    public function fetch();
}
