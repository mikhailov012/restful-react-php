<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: amikhailov
 * Date: 24.12.2019
 * Time: 18:52
 */

namespace App\Controllers\Order;


use App\Controllers\OrderController;
use App\Core\JsonResponse;
use App\Exceptions\OrderNotFound;
use App\Storage\OrderStorage;
use Exception;
use Psr\Http\Message\ServerRequestInterface;

final class DeleteOrder extends OrderController
{
    public function __construct(OrderStorage $storage)
    {
        parent::__construct($storage);
    }

    public function __invoke(ServerRequestInterface $request, string $id)
    {
        return $this->storage->delete((int)$id)
            ->then(
                function () {
                    return JsonResponse::ok([]);
                }
            )
            ->otherwise(
                function (OrderNotFound $error) {
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