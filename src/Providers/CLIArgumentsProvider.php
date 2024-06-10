<?php

namespace App\Providers;

use App\Interfaces\ArgumentsProviderInterface;
use App\Exceptions\ToFewCliArgumentsException;

class CLIArgumentsProvider implements ArgumentsProviderInterface
{
    const FILENAME_ARGUMENT_POSITION = 1;

    private array $argv;

    public function __construct()
    {
        global $argv;
        $this->argv = $argv;

        if(!$this->isFileNameArgumentPresent()) {
            throw new ToFewCliArgumentsException('File name with transactions is not specified.');
        }

    }

    public function getTransactionFilePath(): string
    {
        return $this->getGetFilenameArgument();
    }

    private function getGetFilenameArgument(): string
    {
        return $this->argv[self::FILENAME_ARGUMENT_POSITION];
    }

    private function isFileNameArgumentPresent(): bool
    {
        return count($this->argv) > self::FILENAME_ARGUMENT_POSITION;
    }
}
