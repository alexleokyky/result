<?php

namespace App\Providers;

use App\Interfaces\ArgumentsProviderInterface;
use App\Interfaces\FileFetchDataServiceInterface;
use App\Models\Transaction;
use App\Services\CommissionCalculator\TransactionsDataProviderInterface;
use App\Services\Fetch\FileFetchDataService;
use Generator;

class TransactionsDataProvider implements TransactionsDataProviderInterface
{
    private ArgumentsProviderInterface $argumentsProvider;

    private FileFetchDataServiceInterface $fetchService;

    public function __construct()
    {
        $this->argumentsProvider = new CLIArgumentsProvider();
        $this->fetchService = new FileFetchDataService();
    }

    protected function hydrateModel(string $rawData): Transaction {
        $data = json_decode($rawData, true);
        return new Transaction($data['bin'], $data['amount'], $data['currency']);
    }

    /**
     * return Generator
     *
     * @return Generator<Transaction>
     */
    public function getTransactionsData(): Generator
    {
        $this->fetchService->setPath(
            $this->argumentsProvider->getTransactionFilePath()
        );

        foreach ($this->fetchService->fetchLine() as $line) {
            yield $this->hydrateModel($line);
        }
    }
}
