<?php

namespace App\Services\Fetch;

use App\Interfaces\FetchDataServiceInterface;
use CurlHandle;

class HttpFetchDataService extends AbstractFetchDataService implements FetchDataServiceInterface
{
    const OPTION_KEY_QUERY_STRING_PARAMETERS = 'query_string';

    const OPTION_KEY_HEADERS = 'headers';

    private ?CurlHandle $ch;

    public function fetch(): string
    {
        $this->addQueryString();

        $this->ch = curl_init($this->path);
        curl_setopt($this->ch, CURLOPT_HTTPGET, true);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);

        $this->addHeaders();
        
        $resultStr = curl_exec($this->ch);
        curl_close($this->ch);
        unset($this->ch);
        return $resultStr;
    }

    private function addQueryString(): void
    {
        if ($this->isOptionDefined(self::OPTION_KEY_QUERY_STRING_PARAMETERS)) {
            $this->path .= $this->parseQueryString();
        }
    }

    private function addHeaders() : void
    {
        if ($this->isOptionDefined(self::OPTION_KEY_HEADERS)) {
            curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->options[self::OPTION_KEY_HEADERS]);
        }
    }

    private function isOptionDefined(string $optionKey) : bool
    {
        return array_key_exists($optionKey, $this->options) &&
            count($this->options[$optionKey]);
    }

    private function parseQueryString(): string
    {
        $queryString = '';

        foreach($this->options[self::OPTION_KEY_QUERY_STRING_PARAMETERS] as $name => $value) {
            $queryString .= "&{$name}={$value}";
        }

        $query = parse_url($this->path, PHP_URL_QUERY);

        if (!$query) {
            $queryString = '?' . substr($queryString, 1);
        }

        return $queryString;
    }
}
