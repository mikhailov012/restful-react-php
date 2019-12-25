<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: amikhailov
 * Date: 24.12.2019
 * Time: 18:31
 */

namespace App\Controllers\Product;


use App\Controllers\ProductController;
use App\Core\JsonResponse;
use App\Models\Product;
use App\Storage\ProductStorage;
use App\Validators\ProductValidator;
use Exception;
use Psr\Http\Message\ServerRequestInterface;

final class CreateProduct extends ProductController
{
    public function __construct(ProductStorage $storage)
    {
        parent::__construct($storage);
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $input = new ProductValidator($request);
        $input->validate();

        return $this->storage->create($input->name(), $input->price())
            ->then(
                function (Product $product) {
                    return JsonResponse::ok($product->toArray());
                }
            )
            ->otherwise(
                function (Exception $exception) {
                    return JsonResponse::internalServerError($exception->getMessage());
                }
            );
    }
}