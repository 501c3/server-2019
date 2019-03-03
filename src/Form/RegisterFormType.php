<?php
/**
 * Copyright (c) 2019. Mark Garber.  All rights reserved.
 */

/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 2/15/19
 * Time: 5:01 PM
 */

namespace App\Form;

use App\Form\Model\Address;
use App\Form\Model\Contact;
use App\Form\Model\Name;
use App\Form\Model\Registration;
use App\Repository\Access\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

class RegisterFormType extends AbstractType
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {

        $this->userRepository = $userRepository;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',FullNameType::class)
            ->add('address', FullAddressType::class)
            ->add('contact', FullContactType::class)
            ->add('agree',CheckboxType::class,[
                'attr'=>[
                    'label'=>'Agree to terms.',
                    'required'=>true],
                'constraints'=>[
                    new IsTrue(['message'=>'Please agree to terms.'])
                ]]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'=> Registration::class,
            'required'=>true,
            'empty_data'=> new Registration(new Name(),new Address(),new Contact(),false)
        ]);
    }

}