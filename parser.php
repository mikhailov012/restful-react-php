<?php

use Clue\React\Buzz\Browser;
use Clue\React\Socks\Client as SocksClient;
use Symfony\Component\DomCrawler\Crawler;

require 'vendor/autoload.php';

$loop = React\EventLoop\Factory::create();

//$proxy = new SocksClient('184.178.172.13:15311', new \React\Socket\Connector($loop));
//$client = new Browser($loop, new \React\Socket\Connector($loop, ['tcp' => $proxy]));

$client = new Browser($loop);

$scripts = [];

$client->get('https://understat.com/league/EPL')
    ->then(function(\Psr\Http\Message\ResponseInterface $response) use (&$scripts) {
        $crawler = new Crawler((string) $response->getBody());

        $crawler->filter('script')->each(function (Crawler $node, $i) use (&$scripts) {

            if (strpos($node->text(), 'JSON.parse(') !== false && strpos($node->text(), 'Data') !== false) {

                $matches = [];

                preg_match_all("/JSON.parse\(\'(.*?)\'\)/", $node->text(), $matches);

                $json = $matches[1][0];

                $data = preg_replace_callback('#\\\\x([[:xdigit:]]{2})#ism', function($matches) {
                    return chr(hexdec($matches[1]));
                }, $json);

                $scripts[] = json_decode($data, true);
            }
        });

    })->otherwise(function (Exception $exception) {
        var_dump($exception->getMessage());
    });

$loop->run();

var_dump(count($scripts), count($scripts[0]),  count($scripts[1]),  count($scripts[2]));die();
