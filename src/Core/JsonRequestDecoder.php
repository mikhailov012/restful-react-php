<?php


namespace App\Core;


use Psr\Http\Message\ServerRequestInterface;

class JsonRequestDecoder
{
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        if ($request->getHeaderLine('Content-type') === 'application/json') {
            $request = $request->withParsedBody(
                json_decode((string)$request->getBody()->getContents(), true)
            );
        }

        return $next($request);
    }
}