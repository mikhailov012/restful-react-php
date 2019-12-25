<?php
/**
 * Created by PhpStorm.
 * User: amikhailov
 * Date: 24.12.2019
 * Time: 19:12
 */

namespace App\Core;


use React\Http\Response;

final class JsonResponse extends Response
{
    public function __construct(int $statusCode, $data = null)
    {
        if (is_array($data) || is_object($data)) {
            $data = json_encode($data);
        }

        parent::__construct(
            $statusCode,
            ['Content-Type' => 'application/json'],
            $data
        );
    }

    public static function ok($data): self
    {
        return new self(200, $data);
    }

    public static function internalServerError(string $reason): self
    {
        return new self(500, ['message' => $reason]);
    }

    public static function notFound(): self
    {
        return new self(404);
    }
}