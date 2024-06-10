<?php 

namespace App\Services\CommissionCalculator;

use App\Models\Transaction;

interface ExchangeRateDataProviderInteface
{
    public function getExchangeRate(Transaction $transaction): float;
}
