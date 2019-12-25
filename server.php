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
use App\Core\JsonRequestDecoder;
use App\Core\Router;
use App\Storage\OrderStorage;
use App\Storage\ProductStorage;
use Dotenv\Dotenv;
use FastRoute\DataGenerator\GroupCountBased;
use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std;
use React\EventLoop\Factory;
use React\Filesystem\Filesystem;
use React\Http\Server;

require 'vendor/autoload.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

(Dotenv::createImmutable(__DIR__))->load();

$loop = Factory::create();
$mysql = new \React\MySQL\Factory($loop);

$uri = getenv('DB_USER') . ':' . getenv('DB_PASS') . '@' . getenv('DB_HOST') . '/' . getenv('DB_NAME');
$connection = $mysql->createLazyConnection($uri);
$productStorage = new ProductStorage($connection);
$orderStorage = new OrderStorage($connection);

$routes = new RouteCollector(new Std(), new GroupCountBased());

$routes->get('/', function () {
    return new \React\Http\Response(200, ['Content-Type' => 'text/html'], "<h1>ReactPHP</h1>");
});

$routes->get('/products', new GetAllProducts($productStorage));
$routes->post('/products', new CreateProduct($productStorage));
$routes->get('/products/{id:\d+}', new GetProductById($productStorage));
$routes->put('/products/{id:\d+}', new UpdateProduct($productStorage));
$routes->delete('/products/{id:\d+}', new DeleteProduct($productStorage));

$routes->get('/orders', new GetAllOrders($orderStorage));
$routes->post('/orders', new CreateOrder($orderStorage));
$routes->get('/orders/{id:\d+}', new GetOrderById($orderStorage));
$routes->delete('/orders/{id:\d+}', new DeleteOrder($orderStorage));

$middleware = [
    new ErrorHandler(),
    new JsonRequestDecoder(),
    new Router($routes)
];

$server = new Server($middleware);

$socket = new \React\Socket\Server('127.0.0.1:8888', $loop);
$server->listen($socket);

$server->on('error', function (Throwable $error) {
    echo 'Error: ' . $error->getMessage() . PHP_EOL;
});

echo "Listening on " . str_replace('tcp', 'http', $socket->getAddress()) . PHP_EOL;

$loop->run();