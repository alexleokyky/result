<?php 

namespace App\Services\CommissionCalculator;

use Generator;
use App\Models\Transaction;

interface TransactionsDataProviderInterface
{
    /**
     * return Generator
     *
     * @return Generator<Transaction>
     */
    public function getTransactionsData(): Generator;
}
