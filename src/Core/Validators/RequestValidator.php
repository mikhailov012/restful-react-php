<?php


namespace App\Core\Validators;


use Psr\Http\Message\ServerRequestInterface;

class RequestValidator
{
    protected $request;

    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
    }
}