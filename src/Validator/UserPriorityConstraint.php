<?php

namespace App\Validator;


use Symfony\Component\Validator\Constraint;

#[\Attribute] 
class UserPriorityConstraint extends Constraint
{
public string $message = 'VALIDATION.PRIORITY.HIGHER_THAN_USER';
}
