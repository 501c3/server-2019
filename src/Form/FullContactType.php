<?php
/**
 * Copyright (c) 2019. Mark Garber.  All rights reserved.
 */

/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 2/15/19
 * Time: 7:20 PM
 */

namespace App\Form;


use App\Form\Model\Contact;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class FullContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('phone', TelType::class,[
                'attr'=>['size'=>19,'placeholder'=>'Phone','class'=>'form-control'],
                'required'=>false])
            ->add('mobile',TelType::class,[

                'attr'=>['size'=>19,'placeholder'=>'Mobile', 'class'=>'form-control'],
                'required'=>true,
                'constraints'=>[
                    new NotBlank(['message'=>'Phone number required.'])
                ]])
            ->add('email',EmailType::class, [
                'attr'=>['size'=>42,'placeholder'=>'Email','class'=>'form-control'],
                'required'=>true,
                'constraints'=>[
                    new NotBlank(['message'=>'Email required.'])
                ]])
            ->add('username', null, [
                'attr'=>['size'=>19,'placeholder'=>'Username','class'=>'form-control'],
                'required'=>true,
                'constraints'=>[
                    new NotBlank(['message'=>'Username required.'])
                ]])
            ->add('password', RepeatedType::class,
                ['type'=>PasswordType::class,
                 'invalid_message'=>'Password fields must match',
                 'options'=>['attr'=>['class'=>'password-field form-control']],
                 'required'=>true,
                 'constraints'=>[
                     new NotBlank(['message'=>'Password required.']),
                     new Length(['min'=>7])

                 ],
                 'first_options' => ['attr' => ['size'=>'19', 'placeholder'=>'Password','class'=>'form-control'],
                                     'required'=>true],
                 'second_options'=> ['attr' => ['size'=>'19', 'placeholder'=>'Repeat Password','class'=>'form-control'],
                                     'required'=>true]
                ]);


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'=> Contact::class
        ]);
    }
}