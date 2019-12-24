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

final class GetAllOrders
{
    public function __invoke(ServerRequestInterface $request)
    {
        return JsonResponse::ok(json_encode(['message' => 'GET request to /orders']));
    }
}