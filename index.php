<?php

require('./vendor/autoload.php');

use Dotenv\Dotenv;
use App\Main;

$dotenv = Dotenv::createImmutable(__DIR__);

$dotenv->load();
$dotenv->required([
    'BIN_API_BASE_URL',
    'EXCHANGE_API_URL',
    'EXCHANGE_API_KEY',
    'CALCULATION_CURRENCY',
    'COMMISSION_EU',
    'COMMISSION_NON_EU',
]);

new Main();
