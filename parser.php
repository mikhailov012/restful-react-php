<?php

use Clue\React\Buzz\Browser;
use Symfony\Component\DomCrawler\Crawler;

require 'vendor/autoload.php';

$loop = React\EventLoop\Factory::create();
$client = new Browser($loop);

$client->get('https://understat.com/team/Liverpool')
    ->then(function(\Psr\Http\Message\ResponseInterface $response) {
        $crawler = new Crawler((string) $response->getBody());

        $scripts = [];

        $crawler->filter('script')->each(function (Crawler $node, $i) use (&$scripts) {

            if (strpos($node->text(), 'JSON.parse(') !== false && strpos($node->text(), 'Data') !== false) {

                $matches = [];

                preg_match_all("/JSON.parse\(\'(.*?)\'\)/", $node->text(), $matches);

                $json = $matches[1][0];

                $data = preg_replace_callback('#\\\\x([[:xdigit:]]{2})#ism', function($matches)
                {
                    return chr(hexdec($matches[1]));
                },
                    $json);

                $scripts[] = json_decode($data, true);
            }
        });



        var_dump(count($scripts), count($scripts[0]),  count($scripts[1]),  count($scripts[2]));die();


    });

$loop->run();
