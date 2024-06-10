<?php

namespace App\Tests\Unit;

use App\Exceptions\CardEmitentDataError;
use App\Services\CommissionCalculator\CommissionCalculator;
use PHPUnit\Framework\TestCase;

class CommissionCalculatorTest extends TestCase
{
    private CommissionCalculator $commissionCaclulator;

    private $mockResults = [
        '1.00',
        '0.46',
        '1.18',
        '23.50'
    ];

    protected function setUp(): void
    {
        global $argv;
        $argv[1] = './input.txt';
        $this->commissionCaclulator = new CommissionCalculator();
        
    }

    public function testGetResult()
    { 
        $results = [];
        $this->expectOutputString("Error with transaction: 41417360. Incorrect card issuer data\n");

        foreach ($this->commissionCaclulator->getResults() as $result) {
            $results[] = $result;
        }

        $this->assertEquals($this->mockResults, $results);
    }
}
