<?php


namespace App\Validators;


use App\Core\Validators\RequestValidator;
use App\Core\Validators\RequestValidatorInterface;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Validator;

class ProductValidator extends RequestValidator implements RequestValidatorInterface
{
    public function __construct(ServerRequestInterface $request)
    {
        parent::__construct($request);
    }

    public function validate(): void
    {
        $nameValidator = Validator::key('name', Validator::allOf(Validator::notBlank(), Validator::stringType()))->setName('name');
        $priceValidator = Validator::key('price', Validator::allOf(Validator::notBlank(), Validator::numeric(), Validator::positive()))->setName('price');

        Validator::allOf($nameValidator, $priceValidator)->assert($this->request->getParsedBody());
    }

    public function name(): string
    {
        return $this->request->getParsedBody()['name'];
    }

    public function price(): float
    {
        return (float)$this->request->getParsedBody()['price'];
    }
}