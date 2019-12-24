<?php
/**
 * Created by PhpStorm.
 * User: amikhailov
 * Date: 24.12.2019
 * Time: 17:57
 */

use App\Controllers\Order\CreateOrder;
use App\Controllers\Order\DeleteOrder;
use App\Controllers\Order\GetAllOrders;
use App\Controllers\Order\GetOrderById;
use App\Controllers\Product\CreateProduct;
use App\Controllers\Product\DeleteProduct;
use App\Controllers\Product\GetAllProducts;
use App\Controllers\Product\GetProductById;
use App\Controllers\Product\UpdateProduct;
use App\Core\ErrorHandler;
use App\Core\Router;
use FastRoute\DataGenerator\GroupCountBased;
use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std;
use React\EventLoop\Factory;
use React\Http\Server;

require 'vendor/autoload.php';

$loop = Factory::create();

$jsonHeader = ['Content-Type' => 'application/json'];

$routes = new RouteCollector(new Std(), new GroupCountBased());

$routes->get('/products', new GetAllProducts());
$routes->post('/products', new CreateProduct());
$routes->get('/products/{id:\d+}', new GetProductById());
$routes->put('/products/{id:\d+}', new UpdateProduct());
$routes->delete('/products/{id:\d+}', new DeleteProduct());

$routes->get('/orders', new GetAllOrders());
$routes->post('/orders', new CreateOrder());
$routes->get('/orders/{id:\d+}', new GetOrderById());
$routes->delete('/orders/{id:\d+}', new DeleteOrder());

$server = new Server([new ErrorHandler(), new Router($routes)]);

$socket = new \React\Socket\Server('127.0.0.1:8888', $loop);
$server->listen($socket);

$server->on('error', function (Throwable $error) {
    echo 'Error: ' . $error->getMessage() . PHP_EOL;
});

echo "Listening on " . str_replace('tcp', 'http', $socket->getAddress()) . PHP_EOL;

$loop->run();