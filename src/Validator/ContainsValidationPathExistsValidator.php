<?php

namespace App\Validator;

use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Intl\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ContainsValidationPathExistsValidator extends ConstraintValidator
{
//    private ValidationPathRepository $validationPathRepository;
//
//    public function __construct(ValidationPathRepository $validationPathRepository)
//    {
//        $this->validationPathRepository = $validationPathRepository;
//    }
//
    public function validate($value, Constraint $constraint): void
    {
       /* if (!$value instanceof ValidateEntityInterface) {
            throw new \UnexpectedValueException($value, ValidateEntityInterface::class);
        }

        if (!$constraint instanceof ContainsValidationPathExists) {
            throw new UnexpectedTypeException($constraint, ContainsValidationPathExists::class);
        }

        $validationPath = $this->validationPathRepository->findByTarget($value);

        if (!$validationPath) {
            $this->context->buildViolation($constraint->message)
                ->atPath('validationPath')
                ->addViolation();
        }*/

    }
}
