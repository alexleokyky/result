<?php

namespace App\Interfaces;

use Generator;

interface FileFetchDataServiceInterface extends FetchDataServiceInterface
{
    /**
     * @return Generator<string>
     */
    public function fetchLine(): Generator;
}
