<?php
/**
 * Copyright (c) 2019. Mark Garber.  All rights reserved.
 */

/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 2/15/19
 * Time: 5:16 PM
 */

namespace App\Form;
use App\Form\Model\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;


class FullAddressType extends AbstractType
{
   public function buildForm(FormBuilderInterface $builder, array $options)
   {
      $builder
          ->add('street',null,[
              'attr'=>['size'=>50,'placeholder'=>'Street','class'=>'form-control','style'=>"width: 25em" ],
              'required'=>false,
              'constraints'=>[
                  new NotBlank(['message'=>'Street number and name required.'])
              ]])
          ->add('department', null,[
              'attr'=>['size'=>50,'placeholder'=>'Department or Apartment #','class'=>'form-control',
                       'style'=>"width: 25em"]])
          ->add('country',CountryType::class,[
              'attr'=>['placeholder'=>'Country','class'=>'btn','style'=>'margin-bottom: 3px'],
              'preferred_choices'=>['US'],
              'constraints'=>[
                  new NotBlank(['message'=>'Country required'])
              ]])
          ->add('city', null,[
              'attr'=>['size'=>17,'placeholder'=>'City','class'=>'form-control','style'=>"width: 10em"  ],
              'required'=>true,
              'constraints'=>[
                  new NotBlank(['message'=>'City name required.'])
              ]])
          ->add('state',null,[
              'attr'=>['size'=>5,'placeholder'=>'State','class'=>'form-control','style'=>"width: 3em"],
              'required'=>true,
              'constraints'=>[
                  new NotBlank(['message'=>'State name required.'])
              ]])
          ->add('postal', null,['attr'=>[
              'size'=>8,'placeholder'=>'Postal or Zip','class'=>'form-control','style'=>"width: 8em"],
              'required'=>true,
              'constraints'=>[
                  new NotBlank(['message'=>'Postal or zip code required.'])
              ]]);
   }

   public function configureOptions(OptionsResolver $resolver)
   {
        $resolver->setDefaults([
            'data_class' => Address::class
        ]);
   }
}