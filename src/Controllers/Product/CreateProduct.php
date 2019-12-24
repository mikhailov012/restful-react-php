<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: amikhailov
 * Date: 24.12.2019
 * Time: 18:31
 */

namespace App\Controllers\Product;


use App\Core\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;

final class CreateProduct
{
    public function __invoke(ServerRequestInterface $request)
    {
        $data = $request->getParsedBody();

        return JsonResponse::ok(json_encode([
            'message' => 'POST request to /products',
            'product' => $data
        ]));
    }
}