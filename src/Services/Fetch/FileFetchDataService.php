<?php

namespace App\Services\Fetch;

use App\Interfaces\FileFetchDataServiceInterface;
use Generator;

class FileFetchDataService extends AbstractFetchDataService implements FileFetchDataServiceInterface
{
    protected array $options = [
        'use_include_path' => false,
        'context' => null,
        'offset' => 0,
        'length' => null,
        'mode' => 'r',
    ];

    protected $file;

    public function fetch(): string
    {       
        return file_get_contents(
            $this->path,
            $this->options['use_include_path'],
            $this->options['context'],
            $this->options['offset'],
            $this->options['length'],
        );
    }

    /**
     * @inheritDoc
     */
    public function fetchLine(): Generator
    {
        if (null === $this->file) {
            $this->file = fopen($this->path, $this->options['mode']);
        }

        try {
             while ($line = fgets($this->file)) {
                yield $line;
             }
        } finally {
            fclose($this->file);
            unset($this->file);
        }
    }
}
