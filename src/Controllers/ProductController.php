<?php


namespace App\Controllers;


use App\Storage\ProductStorage;

class ProductController
{
    /**
     * @var ProductStorage
     */
    protected $storage;

    public function __construct(ProductStorage $storage)
    {
        $this->storage = $storage;
    }
}