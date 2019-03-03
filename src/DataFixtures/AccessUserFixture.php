<?php

namespace App\DataFixtures;

use App\Entity\Access\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccessUserFixture extends AppFixtures
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {

        $this->passwordEncoder = $passwordEncoder;
    }


    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(10,'main_users', function($i) {
           $user = new User();
           $user->setEmail(sprintf('mgarber%d@georgiadancesport.org',$i));
           $user->setUsername($this->faker->Username);
           $user->setEnabled(true);
           $user->setRoles(['ROLE_USER']);
           $user->setPassword($this->passwordEncoder->encodePassword(
               $user,
               'engage'
           ));
           return $user;
        });
        $this->createMany(3,'admin_users', function($i) {
            $user = new User();
            $user->setEmail(sprintf('admin%d@georgiadancesport.org',$i));
            $user->setUsername($this->faker->Username);
            $user->setEnabled(true);
            $user->setRoles(['ROLE_ADMIN']);
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'engage'
            ));
            return $user;
        });
        $manager->flush();
    }
}
