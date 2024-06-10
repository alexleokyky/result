<?php

namespace App\Services\Fetch;

abstract class AbstractFetchDataService
{
    protected array $options = [];

    public function __construct(
        protected ?string $path = null,
        array $options = []
    )
    {
        $this->setOptions($options);
    }

    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }
    
    public function setOptions(array $options = []): static
    {
        $this->options = array_merge($this->options, $options);

        return $this;
    }
}
