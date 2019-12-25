<?php


namespace App\Storage;


use App\Core\Exceptions\ModelNotFound;
use App\Core\Storage\Storage;
use App\Core\Storage\StorageInterface;
use App\Exceptions\OrderNotFound;
use App\Models\Model;
use App\Models\Order;
use React\MySQL\ConnectionInterface;
use React\MySQL\QueryResult;
use React\Promise\PromiseInterface;

class OrderStorage extends Storage implements StorageInterface
{
    protected const TABLE_NAME = 'orders';

    protected const PRIMARY_KEY = 'id';

    protected const TABLE_KEYS = [
        'product_id',
        'quantity'
    ];

    public function __construct(ConnectionInterface $connection)
    {
        parent::__construct($connection, self::TABLE_NAME, self::TABLE_KEYS);
    }

    public function create(int $product_id = null, int $quantity = null): PromiseInterface
    {
        return $this->insertToStorage([$product_id, $quantity])
            ->then(function (QueryResult $result) use ($product_id, $quantity) {
                return new Order($result->insertId, $product_id, $quantity);
            });
    }

    public function getAll(): PromiseInterface
    {
        return $this->getAllFromStorage()
            ->then(function (QueryResult $result) {
                return array_map(function ($row) {
                    return $this->mapRow($row);
                }, $result->resultRows);
            });
    }

    public function getById(int $id): PromiseInterface
    {
        return $this->getByIdFromStorage($id)
            ->then(
                function (Model $model) {
                    return $this->mapRow($this->fromModelToRow($model, $this->getStorageKeys()));
                }
            )
            ->otherwise(
                function (ModelNotFound $error) {
                    throw new OrderNotFound();
                }
            );
    }

    public function update(int $id, int $product_id = null, int $quantity = null): PromiseInterface
    {
        return $this->updateInStorage($id, [$product_id, $quantity])
            ->then(
                function (QueryResult $result) {
                    return;
                }
            )->otherwise(
                function (ModelNotFound $error) {
                    throw new OrderNotFound();
                }
            );
    }

    public function delete(int $id): PromiseInterface
    {
        return $this->deleteFromStorage($id)
            ->then(
                function (QueryResult $result) {
                    return;
                }
            )->otherwise(
                function (ModelNotFound $error) {
                    throw new OrderNotFound();
                }
            );
    }

    public function mapRow(array $row)
    {
        return new Order((int)$row['id'], (int)$row['product_id'], (int)$row['quantity']);
    }

    public function getStorageKeys(): array
    {
        return array_merge([self::PRIMARY_KEY],self::TABLE_KEYS);
    }
}