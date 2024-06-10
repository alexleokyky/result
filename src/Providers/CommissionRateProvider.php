<?php

namespace App\Providers;

use App\Enum\EUAlpha2CodesEnum;
use App\Models\CardEmitent;
use App\Models\Transaction;
use App\Interfaces\BankCardEmitentDataProviderInteface;
use App\Services\CommissionCalculator\CommissionRateProviderInterface;

class CommissionRateProvider implements CommissionRateProviderInterface
{
    private float $commissionEU;

    private float $commissionNonEU;

    private BankCardEmitentDataProviderInteface $cardEmitentDataProvider;

    public function __construct()
    {
        $this->commissionEU = (float) $_ENV['COMMISSION_EU'];
        $this->commissionNonEU = (float) $_ENV['COMMISSION_NON_EU'];
        $this->cardEmitentDataProvider = new BankCardEmitentDataProvider();
    }

    public function getCommission(Transaction $transaction): float
    {
        if ($this->isCardEmitentFromEU($this->getCardEmitent($transaction))) {
            return $this->commissionEU;
        }

        return $this->commissionNonEU;
    }

    private function isCardEmitentFromEU(CardEmitent $cardEmitent): bool
    {
        return null !== EUAlpha2CodesEnum::tryFrom($cardEmitent->getAlpha2Code());
    }

    private function getCardEmitent(Transaction $transaction): CardEmitent
    {
        return $this->cardEmitentDataProvider->getByBIN($transaction->getBin());
    }
}
