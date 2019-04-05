<?php

namespace App\Validator;
;
use App\Form\Model\Registration;
use App\Repository\Access\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueUserValidator extends ConstraintValidator
{

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint App\Validator\UniqueUser */
        /** @var  Registration $value */
        $contact = $value->getContact();
        $username = $contact->getUsername();
        $userByName = $this->userRepository->findOneBy(['username'=>$username]);
        if($userByName) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{value}}', $username)->addViolation();
        }
    }
}
