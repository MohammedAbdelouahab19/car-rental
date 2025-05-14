<?php

namespace App\Service;

use Symfony\Component\Validator\Validator\ValidatorInterface;

final class Validator
{
    public const string UNIQUE_ENTITY = 'VALIDATION.MIXED.UNIQUE';
    public const string NOT_BLANK = 'VALIDATION.MIXED.REQUIRED';
    public const string STARTING_DATE_GREATER_OR_EQ_THAN_TODAY = 'STARTING_DATE_GREATER_OR_EQ_THAN_TODAY';
    public const string END_DATE_GREATER_OR_EQ_THAN_START_DATE = 'END_DATE_GREATER_OR_EQ_THAN_START_DATE';
    public const string CAR_NOT_AVAILABLE_IN_THIS_PERIOD = 'CAR_NOT_AVAILABLE_IN_THIS_PERIOD';

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
