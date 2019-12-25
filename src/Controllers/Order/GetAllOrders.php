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
use App\Storage\OrderStorage;
use Psr\Http\Message\ServerRequestInterface;

final class GetAllOrders extends OrderController
{
    public function __construct(OrderStorage $storage)
    {
        parent::__construct($storage);
    }

    public function __invoke(ServerRequestInterface $request)
    {
        return $this->storage->getAll()
            ->then(
                function (array $orders) {
                    return JsonResponse::ok($orders);
                }
            )
            ->otherwise(
                function (\Exception $exception) {
                    return JsonResponse::internalServerError($exception->getMessage());
                }
            );
    }
}