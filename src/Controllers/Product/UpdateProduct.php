<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: amikhailov
 * Date: 24.12.2019
 * Time: 18:46
 */

namespace App\Controllers\Product;


use App\Core\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;

final class UpdateProduct
{
    public function __invoke(ServerRequestInterface $request, string $id)
    {
        return JsonResponse::ok(json_encode(['message' => "PUT request to /products/{$id}"]));
    }
}