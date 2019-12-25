<?php declare(strict_types=1);

return __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

(Dotenv::createImmutable(__DIR__))->load();

return [
    'dbname' => getenv('DB_NAME'),
    'user' => getenv('DB_USER'),
    'password' => getenv('DB_PASS'),
    'host' => getenv('DB_HOST'),
    'driver' => getenv('DB_DRIVER')
];