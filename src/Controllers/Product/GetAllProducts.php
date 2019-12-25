<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: amikhailov
 * Date: 24.12.2019
 * Time: 18:30
 */

namespace App\Controllers\Product;


use App\Controllers\ProductController;
use App\Core\JsonResponse;
use App\Storage\ProductStorage;
use Psr\Http\Message\ServerRequestInterface;

final class GetAllProducts extends ProductController
{
    public function __construct(ProductStorage $storage)
    {
        parent::__construct($storage);
    }

    public function __invoke(ServerRequestInterface $request)
    {
        return $this->storage->getAll()
            ->then(
                function (array $products) {
                    return JsonResponse::ok($products);
                }
            )
            ->otherwise(
                function (\Exception $exception) {
                    return JsonResponse::internalServerError($exception->getMessage());
                }
            );
    }
}