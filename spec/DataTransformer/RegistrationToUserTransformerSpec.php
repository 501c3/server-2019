<?php

namespace spec\App\DataTransformer;

use App\DataTransformer\RegistrationException;
use App\Entity\Access\User;
use App\Form\Model\Address;
use App\Form\Model\Contact;
use App\Form\Model\Name;
use App\Form\Model\Registration;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Security\Core\Encoder\Argon2iPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class RegistrationToUserTransformerSpec extends ObjectBehavior
{
    const ENCRYPTED_PASSWORD = '$argon2i$v=19$m=1024,t=2,p=2$bHQxMWdoRHoxckVqT1JieQ$TkKoxuLvajf5ZoAfeFvbv0xVYZyz11gQ/7NXiIeG/vI';

    /** @var UserPasswordEncoder */
    private $passwordEncoder;

    private function buildExpectedUser(Registration $registration)
    {
        $name = $registration->getName();
        $address = $registration->getAddress();
        $contact = $registration->getContact();
        // $agree = $registration->getAddress();
        $info = [
            'name'=>[
                'title'=>$name->getTitle(),
                'first'=>$name->getFirst(),
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
            ->setRoles(['ROLE_USER'])
            ->setEmail($contact->getEmail())
            ->setPassword(self::ENCRYPTED_PASSWORD)
            ->setInfo($info);
        return $user;
    }


    public function let()
    {
        $factory = new EncoderFactory([User::class=>new Argon2iPasswordEncoder()]);
        $this->passwordEncoder=new UserPasswordEncoder($factory);
        $this->beConstructedWith($this->passwordEncoder);
    }

    public function getMatchers(): array
    {
        return
            ['returnUserObject'=>function($subject){
                   return $subject instanceof User;
                },
        ];
    }


    function it_transforms_registration_to_user()
    {

        $registration = new Registration(
            new Name(null, 'First','MI','Last',null),
            new Address('Street',null, 'Country','City',
                'GA','30328'),
            new Contact('user@email.org','user','password',
                '(999) 999-9999','(999) 999-9999'),
            true
        );
        $expectedUser= $this->buildExpectedUser($registration);
        $this->transform($registration)->shouldReturnUserObject($expectedUser);
    }

    function it_should_recognize_correct_password()
    {
        $registration = new Registration(
            new Name(null, 'First','MI','Last',null),
            new Address('Street',null, 'Country','City',
                'GA','30328'),
            new Contact('user@email.org','user','password',
                '(999) 999-9999','(999) 999-9999'),
            true
        );
        $expectedUser= $this->buildExpectedUser($registration);
        $this->getEncoder()->isPasswordValid($expectedUser,'password')->shouldReturn(true);
        $this->getEncoder()->isPasswordValid($expectedUser,'not_password')->shouldReturn(false);
    }

    function it_should_not_allow_missing_first_and_or_last_name_in_registration()
    {
        $registration = new Registration(
            new Name(null, null,'MI','Last',null),
            new Address('Street',null, 'Country','City','GA','30328'),
            new Contact('user@email.org','user','password',
                '(999) 999-9999','(999) 999-9999'),
            true
        );
        $this->shouldThrow(RegistrationException::class)
            ->during('transform',[$registration]);
    }
}
