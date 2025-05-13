<?php

namespace App\Validator;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UserPriorityConstraintValidator extends ConstraintValidator
{
    private Security $security;
//    private RoleRepository $roleRepository;

    public function __construct(Security $security/*, RoleRepository $roleRepository*/)
    {
        $this->security = $security;
//        $this->roleRepository = $roleRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        $currentUser = $this->security->getUser();
        $userPriority = $currentUser->getRole()->getPriority();
        $roleId = $currentUser->getRole()->getId();

//        $oldPriority = $this->roleRepository->findPriorityById($roleId);

//        if ($value < $userPriority || $value < $oldPriority) {
//            $this->context->buildViolation($constraint->message)
//                ->addViolation();
//        }
    }
}
