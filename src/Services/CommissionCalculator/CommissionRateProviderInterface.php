<?php

namespace App\Services\CommissionCalculator;

use App\Models\Transaction;

interface CommissionRateProviderInterface
{
    public function getCommission(Transaction $transaction): float;
}
