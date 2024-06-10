<?php

namespace App\Interfaces;

interface FetchDataServiceInterface
{
    public function setPath(string $path): static;

    public function setOptions(array $options): static;

    public function fetch(): string;
}
