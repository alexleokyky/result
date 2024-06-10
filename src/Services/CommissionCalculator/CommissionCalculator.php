<?php

namespace App\Services\CommissionCalculator;

use App\Exceptions\CardEmitentDataError;
use App\Exceptions\InvalidPathException;
use App\Exceptions\ToFewCliArgumentsException;
use App\Providers\ExchangeRateDataProvider;
use App\Providers\TransactionsDataProvider;
use App\Models\Transaction;
use App\Providers\CommissionRateProvider;
use Exception;
use Generator;

class CommissionCalculator
{
    const MATH_PRECISION = 10;

    const RESULT_PERCISION = 2;

    private TransactionsDataProviderInterface $transactionsProvider;

    private ExchangeRateDataProviderInteface $exchangeRateProvider;

    private CommissionRateProviderInterface $commissionRateProvider;

    private string $calculationCurrency;

    public function __construct()
    {
        $this->calculationCurrency = $_ENV['CALCULATION_CURRENCY'];

        try {
            $this->transactionsProvider = new TransactionsDataProvider();
            $this->exchangeRateProvider = new ExchangeRateDataProvider();
            $this->commissionRateProvider = new CommissionRateProvider();
        } catch(Exception $exception) {
            if (
                $exception instanceof InvalidPathException
                || $exception instanceof ToFewCliArgumentsException
            ) {
                printf("%s\n", $exception->getMessage());
                die();
            }

            throw $exception;
        }
        

        
    }

    /**
     * Undocumented function
     *
     * @return Generator<string>
     */
    public function getResults(): Generator
    {
        foreach ($this->transactionsProvider->getTransactionsData() as $transaction) {
            try {
               yield $this->ceilResult(
                    bcmul($this->getCalculationCurrencyAmount($transaction), $this->getCommissionRate($transaction), self::MATH_PRECISION)
               );
            } catch (Exception $exception) {
                if (
                    $exception instanceof CardEmitentDataError
                    || $exception instanceof InvalidPathException
                    || $exception instanceof ToFewCliArgumentsException
                ) {
                    printf("%s: %s. %s\n", 'Error with transaction', $transaction->getBin(), $exception->getMessage());
                    continue;
                } else {
                    throw $exception;
                }
            }
        }
    }

    private function ceilResult(float $result): string
    {
        $precisionMultiplicator = bcpow(10, self::RESULT_PERCISION);
        $intResult = bcmul($result, $precisionMultiplicator);
        $floatResult = bcmul($result, $precisionMultiplicator, self::MATH_PRECISION);

        if ($floatResult > $intResult)
        {
            $intResult = bcadd($intResult, 1);
        }

        return bcdiv($intResult, $precisionMultiplicator, self::RESULT_PERCISION);
    }


    private function getCalculationCurrencyAmount(Transaction $transaction): float
    {
        $currencyRate = $this->exchangeRateProvider->getExchangeRate($transaction);

        if ($this->isCalculationCurrency($transaction) || $currencyRate === 0) {
            return $transaction->getAmount();
        }

        return (float) bcdiv($transaction->getAmount(), $currencyRate, self::MATH_PRECISION);
    }

    private function isCalculationCurrency(Transaction $transaction): bool
    {
        return $transaction->getCurrency() == $this->calculationCurrency;
    }

    private function getCommissionRate(Transaction $transaction): float
    {
        return $this->commissionRateProvider->getCommission($transaction);
    }
}
