<?php


namespace App\Storage;


use App\Core\Exceptions\ModelNotFound;
use App\Core\Storage\Storage;
use App\Core\Storage\StorageInterface;
use App\Exceptions\ProductNotFound;
use App\Models\Model;
use App\Models\Product;
use React\MySQL\ConnectionInterface;
use React\MySQL\QueryResult;
use React\Promise\PromiseInterface;

class ProductStorage extends Storage implements StorageInterface
{
    protected const TABLE_NAME = 'products';

    protected const PRIMARY_KEY = 'id';

    protected const TABLE_KEYS = [
        'name',
        'price'
    ];

    public function __construct(ConnectionInterface $connection)
    {
        parent::__construct($connection, self::TABLE_NAME,self::PRIMARY_KEY, self::TABLE_KEYS);
    }

    public function create(string $name = null, float $price = null): PromiseInterface
    {
        return $this->insertToStorage([$name, $price])
            ->then(function (QueryResult $result) use ($name, $price) {
                return new Product($result->insertId, $name, $price);
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
                    return $this->mapRow($this->mapModel($model));
                }
            )
            ->otherwise(
                function (ModelNotFound $error) {
                    throw new ProductNotFound();
                }
            );
    }

    public function update(int $id, string $name = null, float $price = null): PromiseInterface
    {
        return $this->updateInStorage($id,[$name, $price])
            ->then(
                function (QueryResult $result) {
                    return;
                }
            )->otherwise(
                function (ModelNotFound $error) {
                    throw new ProductNotFound();
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
                    throw new ProductNotFound();
                }
            );
    }

    public function mapRow(array $row)
    {
        return new Product((int)$row['id'], $row['name'], (float)$row['price']);
    }
}