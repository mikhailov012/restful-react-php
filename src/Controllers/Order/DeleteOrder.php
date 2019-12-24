<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: amikhailov
 * Date: 24.12.2019
 * Time: 18:52
 */

namespace App\Controllers\Order;


use App\Core\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;

final class DeleteOrder
{
    public function __invoke(ServerRequestInterface $request, string $id)
    {
        return JsonResponse::ok(json_encode(['message' => "DELETE request to /orders/{$id}"]));
    }
}