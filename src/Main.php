<?php

namespace App;

use App\Providers\BINDataProvider;
use App\Providers\ExchangeRateDataProvider;
use App\Providers\TransactionsDataProvider;
use App\Enum\EUAlpha2CodesEnum;
use App\Interfaces\CommissionCalculatorInterface;
use App\Services\CommissionCalculator\CommissionCalculator;

class Main
{
    public function __construct()
    {
        $calculator = new CommissionCalculator();
        
        foreach ($calculator->getResults() as $result) {
            printf("%s\n", $result);
        }
        
        die();
    }
}
