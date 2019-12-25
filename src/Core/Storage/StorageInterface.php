<?php


namespace App\Core\Storage;


use App\Models\Model;
use React\Promise\PromiseInterface;

interface StorageInterface
{
    public function create(): PromiseInterface;

    public function getAll(): PromiseInterface;

    public function getById(int $id): PromiseInterface;

    public function update(int $id): PromiseInterface;

    public function delete(int $id): PromiseInterface;

    public function mapRow(array $row);
}