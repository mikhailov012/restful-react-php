<?php


namespace App\Core\Validators;


interface RequestValidatorInterface
{
    public function validate(): void;
}