<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: amikhailov
 * Date: 24.12.2019
 * Time: 18:46
 */

namespace App\Controllers\Product;


use App\Controllers\ProductController;
use App\Core\JsonResponse;
use App\Exceptions\ProductNotFound;
use App\Storage\ProductStorage;
use App\Validators\ProductValidator;
use Exception;
use Psr\Http\Message\ServerRequestInterface;

final class UpdateProduct extends ProductController
{
    public function __construct(ProductStorage $storage)
    {
        parent::__construct($storage);
    }

    public function __invoke(ServerRequestInterface $request, string $id)
    {
        $input = new ProductValidator($request);
        $input->validate();

        return $this->storage->update((int)$id, $input->name(), $input->price())
            ->then(
                function () {
                    return JsonResponse::ok([]);
                }
            )
            ->otherwise(
                function (ProductNotFound $error) {
                    return JsonResponse::notFound();
                }
            )
            ->otherwise(
                function (Exception $error) {
                    return JsonResponse::internalServerError($error->getMessage());
                }
            );
    }
}