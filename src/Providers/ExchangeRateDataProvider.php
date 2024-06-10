<?php 

namespace App\Providers;

use App\Interfaces\FetchDataServiceInterface;
use App\Models\Transaction;
use App\Services\CommissionCalculator\ExchangeRateDataProviderInteface;
use App\Services\Fetch\FetchDataServiceFactory;

class ExchangeRateDataProvider implements ExchangeRateDataProviderInteface
{
    private string $exchangeApiUrl;

    private FetchDataServiceInterface $fetchService;

    private ?array $exchangeRates = null;

    public function __construct()
    {
        $this->exchangeApiUrl = $_ENV['EXCHANGE_API_URL'];
        $this->fetchService = FetchDataServiceFactory::createForPath($this->exchangeApiUrl);
    }

    public function getExchangeRate(Transaction $transaction): float
    {
        $this->loadRates();

        return $this->exchangeRates[$transaction->getCurrency()];
    }

    private function loadRates(): void
    {
        if (null === $this->exchangeRates) {
            $this->fetchService->setOptions([
                'query_string' => [
                    'access_key' => $_ENV['EXCHANGE_API_KEY'],
                ]
            ]);
            $result = json_decode($this->fetchService->fetch(), true);
            
            if (isset($result['rates'])) {
                $this->exchangeRates = $result['rates'];
            } 
            
        }
    }
}
