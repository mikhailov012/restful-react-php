<?php

use App\Models\Chat\ConnectionPool;
use React\EventLoop\Factory;
use React\Socket\ConnectionInterface;

require 'vendor/autoload.php';

$loop = Factory::create();

$socket = new \React\Socket\Server('127.0.0.1:8888', $loop);
$pool = new ConnectionPool();
$socket->on('connection', function (ConnectionInterface $connection) use ($pool) {
    $pool->add($connection);
});
echo "listening on {$socket->getAddress()}\n";

$loop->run();