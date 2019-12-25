<?php
/**
 * Created by PhpStorm.
 * User: amikhailov
 * Date: 24.12.2019
 * Time: 12:22
 */

use React\Filesystem\Filesystem;

require 'vendor/autoload.php';
require 'Downloader.php';

$loop = \React\EventLoop\Factory::create();
$client = new \Clue\React\Buzz\Browser($loop);

$downloader = new Downloader(
    $client->withOptions(['streaming' => true]),
    Filesystem::create($loop),
    __DIR__ . '/downloads'
);

$downloader->download(...[
    'https://www.sample-videos.com/video123/mp4/720/big_buck_bunny_720p_1mb.mp4',
    'https://www.sample-videos.com/video123/mp4/720/big_buck_bunny_720p_2mb.mp4',
    'https://www.sample-videos.com/video123/mp4/720/big_buck_bunny_720p_5mb.mp4'
]);

$loop->run();