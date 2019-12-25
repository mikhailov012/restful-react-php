<?php


namespace App\Validators;


use App\Core\Validators\RequestValidator;
use App\Core\Validators\RequestValidatorInterface;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Validator;

class OrderValidator extends RequestValidator implements RequestValidatorInterface
{
    public function __construct(ServerRequestInterface $request)
    {
        parent::__construct($request);
    }

    public function validate(): void
    {
        $productIdValidator = Validator::key('product_id', Validator::allOf(Validator::notBlank(), Validator::numeric(), Validator::positive()))->setName('product_id');
        $quantityValidator = Validator::key('quantity', Validator::allOf(Validator::notBlank(), Validator::numeric(), Validator::positive()))->setName('quantity');

        Validator::allOf($productIdValidator, $quantityValidator)->assert($this->request->getParsedBody());
    }

    public function productId()
    {
        return (int)$this->request->getParsedBody()['product_id'];
    }

    public function quantity()
    {
        return (int)$this->request->getParsedBody()['quantity'];
    }
}