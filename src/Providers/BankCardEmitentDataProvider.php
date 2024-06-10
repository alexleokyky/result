<?php

namespace App\Providers;

use App\Exceptions\CardEmitentDataError;
use App\Interfaces\FetchDataServiceInterface;
use App\Models\CardEmitent;
use App\Interfaces\BankCardEmitentDataProviderInteface;
use App\Services\Fetch\FetchDataServiceFactory;

class BankCardEmitentDataProvider implements BankCardEmitentDataProviderInteface
{
    private string $baseUrl;

    private FetchDataServiceInterface $fetchService;

    public function __construct()
    {
        $this->baseUrl = $_ENV['BIN_API_BASE_URL'];
        $this->fetchService = FetchDataServiceFactory::createForPath($this->baseUrl);
        
    }

    public function getByBIN(int $cardBIN): CardEmitent | null
    {
        $cardEmitentData = json_decode($this->fetchBinData($cardBIN), true);

        return $this->hydrateModel($cardEmitentData);
    }

    private function fetchBinData(int $cardBIN): string
    {
        return $this->fetchService->setPath(
            $this->getApiPath($cardBIN)
        )->setOptions([
            'headers' => [
                'Accept-Version: 3'
            ]
        ])->fetch();
    }

    private function getApiPath(int $cardBIN): string
    {
        return rtrim($this->baseUrl,"/") . "/{$cardBIN}" ;
    }

    private function hydrateModel(array $cardEmitentData) : CardEmitent
    {   
        if (empty($cardEmitentData['country'])) {
            throw new CardEmitentDataError('Incorrect card issuer data');
        }

        return new CardEmitent($cardEmitentData['country']['alpha2']);
    }
}
