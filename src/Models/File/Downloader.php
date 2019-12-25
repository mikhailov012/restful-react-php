<?php


namespace App\Models\File;


final class Downloader
{
    private $client;

    private $filesystem;

    private $dir;

    public function __construct(Browser $client, Filesystem $filesystem, $dir)
    {
        $this->client = $client;
        $this->filesystem = $filesystem;
        $this->dir = $dir;
    }

    public function download(string ...$urls): void
    {
        foreach ($urls as $url) {
            $file = $this->openFile($url);

            $this->client->get($url)
                ->then(
                    function (\Psr\Http\Message\ResponseInterface $response) use ($file) {
                        $response->getBody()->pipe($file);
                    });
        }
    }

    private function openFile(string $url): \React\Stream\WritableStreamInterface
    {
        $path = $this->dir . DIRECTORY_SEPARATOR . basename($url);

        return \React\Promise\Stream\unwrapWritable($this->filesystem->file($path)->open('cw'));
    }
}