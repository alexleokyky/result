<?php 

namespace App\Models;

class CardEmitent
{
    public function __construct(
        private string $alpha2Code
    ) {
    }
    
    public function getAlpha2Code(): string
    {
        return $this->alpha2Code;
    }
}
