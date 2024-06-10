<?php

namespace App\Services\Fetch;

use App\Exceptions\InvalidPathException;
use App\Interfaces\FetchDataServiceInterface;

class FetchDataServiceFactory
{
    public static function createForPath(string $path, array $options = []): FetchDataServiceInterface
    {
        if (filter_var($path, FILTER_VALIDATE_URL) !== false) {
            return new HttpFetchDataService($path, $options);
        }
        
        if (file_exists($path) === true) {
            return new FileFetchDataService($path, $options);
        }

        throw new InvalidPathException(sprintf(
            '%s - %s',
            $path,
            'The path you specified is not a valid url or the file path you specified does not exist'
            )
        );
    }
}
