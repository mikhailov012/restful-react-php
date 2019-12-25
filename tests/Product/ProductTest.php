<?php

namespace Product;

use App\Models\Product;
use Psr\Http\Message\ResponseInterface;

class ProductTest extends ProductSetUp
{
    public function testGetProduct()
    {
        $product = $this->getExistedProduct();

        $this->assertTrue(
            $this->product->id == $product->id
        );
    }
}