<?php 

namespace App\Interfaces;

use App\Models\CardEmitent;

interface BankCardEmitentDataProviderInteface
{
    public function getByBIN(int $cartBin): CardEmitent | null;
}
