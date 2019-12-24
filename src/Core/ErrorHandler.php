<?php
/**
 * Created by PhpStorm.
 * User: amikhailov
 * Date: 24.12.2019
 * Time: 19:06
 */

namespace App\Core;


use Psr\Http\Message\ServerRequestInterface;
use React\Http\Response;

class ErrorHandler
{
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        try {

            return $next($request);

        } catch (\Throwable $error) {

            return JsonResponse::internalServerError($error->getMessage());
        }
    }
}