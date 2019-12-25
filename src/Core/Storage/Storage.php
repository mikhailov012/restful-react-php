<?php


namespace App\Core\Storage;


use App\Core\Exceptions\ModelNotFound;
use App\Models\Model;
use App\Models\Product;
use React\MySQL\ConnectionInterface;
use React\MySQL\QueryResult;
use React\Promise\PromiseInterface;

class Storage
{
    /** @var ConnectionInterface */
    public $connection;

    protected const KEYS_DELIMITER = ',';
    /**
     * @var string
     */
    private $tableName;

    /**
     * @var string
     */
    private $primaryKey;

    /**
     * @var array
     */
    private $keys;

    public function __construct(ConnectionInterface $connection, string $tableName, string $primaryKey, array $keys)
    {
        $this->connection = $connection;
        $this->tableName = $tableName;
        $this->keys = $keys;
    }

    protected function insertToStorage(array $values): PromiseInterface
    {
        $keys = $this->getKeysForSql();
        $sqlValues = $this->getValuesForInsertSql();

        $sql = "INSERT INTO {$this->tableName} ($keys) VALUES ($sqlValues)";

        return $this->connection->query($sql, $values);
    }

    /**
     * @return PromiseInterface
     */
    protected function getAllFromStorage(): PromiseInterface
    {
        $keys = $this->getKeysForGetSql();

        $sql = "SELECT $keys FROM {$this->tableName}";

        return $this->connection->query($sql);
    }

    protected function getByIdFromStorage(int $id): PromiseInterface
    {
        $keys = $this->getKeysForGetSql();
        $params = [$id];

        $sql = "SELECT $keys FROM {$this->tableName} WHERE id = ?";


        return $this->connection
            ->query($sql, $params)
            ->then(
                function (QueryResult $result) {
                    if (empty($result->resultRows)) {
                        throw new ModelNotFound();
                    }

                    return (new Model())->toObj($result->resultRows[0]);
                }
            );
    }

    protected function updateInStorage(int $id, array $values): PromiseInterface
    {
        return $this->getByIdFromStorage($id)
            ->then(function (Model $model) use ($values) {
                $sqlKeys = $this->getKeysForUpdateSql();
                $params = array_merge($values,[$model->getKey()]);

                $sql = "UPDATE {$this->tableName} SET $sqlKeys WHERE id = ?";

                return $this->connection->query($sql, $params);
            });
    }

    protected function deleteFromStorage(int $id): PromiseInterface
    {
        return $this->getByIdFromStorage($id)
            ->then(
                function (Model $model) {

                    $sql = "DELETE FROM {$this->tableName} WHERE id = ?";
                    $params = [$model->getKey()];

                    return $this->connection->query($sql, $params);
                }
            );
    }

    protected function mapModel(Model $model): array
    {
        $row = [];

        foreach (array_merge([$this->primaryKey], $this->keys) as $key) {
            if (isset($model->{$key})) {
                $row[$key] = $model->{$key};
            }
        }

        return $row;
    }

    private function getKeysForSql(): string
    {
        return implode(self::KEYS_DELIMITER, $this->keys);
    }

    private function getKeysForGetSql(): string
    {
        return implode(self::KEYS_DELIMITER, array_merge(['id'],$this->keys));
    }

    private function getValuesForInsertSql(): string
    {
        $sqlValues = '';

        foreach ($this->keys as $key) {
            $sqlValues .= '?';
            if (end($this->keys) != $key) $sqlValues .= ',';
        }

        return $sqlValues;
    }

    private function getKeysForUpdateSql(): string
    {
        $sqlKeys = '';

        foreach ($this->keys as $key) {
            $sqlKeys .= "$key = ?";
            if (end($this->keys) != $key) $sqlKeys .= ',';
        }

        return  $sqlKeys;
    }
}