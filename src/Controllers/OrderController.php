<?php


namespace App\Controllers;


use App\Storage\OrderStorage;

class OrderController
{
    /**
     * @var OrderStorage
     */
    protected $storage;

    public function __construct(OrderStorage $storage)
    {
        $this->storage = $storage;
    }
}