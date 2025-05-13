<?php

namespace App\Validator;

use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

#[\Attribute]
class ContainsValidationPathExists extends Constraint
{

    public function __construct(
        public string $message,
//        public  $groups = null,
//        public $payload = null
    ) {
//        parent::__construct(groups: $groups, payload: $payload);
    }

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }

}
