<?php

namespace App\Models;

class Transaction {
    public function __construct(
        private int $bin,
        private float $amount,
        private string $currency
    )
    {}

    public function getBin(): int
    {
        return $this->bin;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}
