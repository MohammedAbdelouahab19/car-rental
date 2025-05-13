<?php

namespace App\Service;

use Symfony\Component\Validator\Validator\ValidatorInterface;

final class Validator
{
    public const string UNIQUE_ENTITY = 'VALIDATION.MIXED.UNIQUE';
    public const string NOT_BLANK = 'VALIDATION.MIXED.REQUIRED';

    public function __construct(
        protected ValidatorInterface $validator
    )
    {
    }

    public function isValid($entity): bool
    {
        return count($this->getErrors($entity)) == 0;
    }

    public function getErrors($entity): array
    {
        $errors = $this->validator->validate($entity);
        if (count($errors) > 0) {
            $_errors = [];
            foreach ($errors as $error){
                $_errors[] = [
                    'propertyPath' => $error->getPropertyPath(),
                    'message' => $error->getMessage(),
                ];
            }
            return $_errors;
        }

        return [];
    }
}
