<?php


namespace App\Models;


class Order extends Model
{
    /**
     * @var int
     */
    public $id;
    /**
     * @var int
     */
    public $product_id;
    /**
     * @var int
     */
    public $quantity;

    public function __construct(int $id, int $product_id, int $quantity)
    {
        $this->id = $id;
        $this->product_id = $product_id;
        $this->quantity = $quantity;
    }
}