<?php

namespace App\Interfaces;

interface ArgumentsProviderInterface
{
    public function getTransactionFilePath(): string;
}
