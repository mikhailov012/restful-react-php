<?php


namespace App\Models;


class Product extends Model
{
    /** @var int */
    public $id;

    /** @var string */
    public $name;

    /** @var float */
    public $price;

    public function __construct(int $id, string $name, float $price)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
    }
}