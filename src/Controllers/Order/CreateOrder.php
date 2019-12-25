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
use App\Models\Order;
use App\Storage\OrderStorage;
use App\Validators\OrderValidator;
use Exception;
use Psr\Http\Message\ServerRequestInterface;

final class CreateOrder extends OrderController
{
    public function __construct(OrderStorage $storage)
    {
        parent::__construct($storage);
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $input = new OrderValidator($request);
        $input->validate();

        return $this->storage->create($input->productId(), $input->quantity())
            ->then(
                function (Order $order) {
                    return JsonResponse::ok($order->toArray());
                }
            )
            ->otherwise(
                function (Exception $exception) {
                    return JsonResponse::internalServerError($exception->getMessage());
                }
            );
    }
}