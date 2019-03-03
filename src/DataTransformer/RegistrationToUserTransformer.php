<?php
/**
 * Copyright (c) 2019. Mark Garber.  All rights reserved.
 */

/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 2/17/19
 * Time: 10:13 PM
 */

namespace App\DataTransformer;


use App\Entity\Access\User;
use App\Form\Model\Address;
use App\Form\Model\Contact;
use App\Form\Model\Name;
use App\Form\Model\Registration;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationToUserTransformer
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;


    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    /**
     * @param mixed $registration
     * @return User|null|void
     * @throws RegistrationException
     */
    public function transform($registration)
    {
       
        if (!$registration) {
            return;
        }

        if (!$registration instanceof Registration) {
            throw new \LogicException('RegisterFormType can only be used with Registration object.');
        }

        $user = $this->transformRegistrationToUser($registration);

        return $user;
    }


//    public function reverseTransform($user)
//    {
//
//    }
//
//    public function getErrorFields() {
//
//    }
//
    public function getEncoder()
    {
        return $this->userPasswordEncoder;
    }

    /**
     * @param Registration $registration
     * @return User
     * @throws RegistrationException
     */
    private function transformRegistrationToUser(Registration $registration) : User
    {
        $errorFields = [];

       /** @var Name $name */
       $name = $registration->getName();
       if(!$name->isValid()) {
           $errorFields['name'] = $name->getErrors();
       }
       /** @var Address $address */
       $address = $registration->getAddress();
       if(!$address->isValid()){
           $errorsFields['address']= $address->getErrors();
       }
       /** @var Contact $contact */
       $contact = $registration->getContact();
       if(!$contact->isValid()){
           $errorFields['contact']= $contact->getErrors();
       }
       $agree = $registration->getAgree();
       if(!$agree) {
           $errorFields['agree']=$agree;
       }
       if(count($errorFields)) {
           throw new RegistrationException(array_keys($errorFields),RegistrationException::MISSING_FIELDS);
       }
       $info = ['name'=>[
                        'title'=>$name->getTitle(),
                        'first'=>$name->getTitle(),
                        'middle'=>$name->getMiddle(),
                        'last'=>$name->getLast(),
                        'suffix'=>$name->getSuffix()
                        ],
                'address'=>[
                        'street'=>$address->getStreet(),
                        'department'=>$address->getDepartment(),
                        'country'=>$address->getCountry(),
                        'state'=>$address->getState(),
                        'postal'=>$address->getPostal()
                ],
                'contact'=>[
                        'mobile'=>$contact->getMobile(),
                        'phone'=>$contact->getPhone()
                ]
           ];
        $user = new User();
        $user->setUsername($contact->getUsername())
             ->setEmail($contact->getEmail())
             ->setRoles(['ROLE_USER'])
             ->setEnabled(false)
             ->setInfo($info);
        $password=$this->userPasswordEncoder->encodePassword($user, $contact->getPassword());
        $user->setPassword($password);
        $user->setCreatedAt(new \DateTime());
        return $user;
    }


//    private function transformUserToRegistration(User $user): Registration
//    {
//
//
//    }
}