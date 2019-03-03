<?php
/**
 * Copyright (c) 2019. Mark Garber.  All rights reserved.
 */

/**
 * Created by PhpStorm.
 * User: mgarber
 * Date: 2/15/19
 * Time: 5:15 PM
 */

namespace App\Form;
use App\Form\Model\Name;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
//use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class FullNameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',null, [
                'attr'=>['size'=>2,'class'=>'form-control','style'=>"width: 2em"],
                'required'=>false])
            ->add('first',null, [
                'attr'=>['size'=>8,'placeholder'=>'First','class'=>'form-control','style'=>"width: 8em"],
                'required'=>true,
                'constraints'=>[
                    new NotBlank([
                        'message'=> 'First name is required.'
                    ])]
                ])
            ->add('middle',null, [
                'attr'=>['size'=>3,'placeholder'=>'M.I.','class'=>'form-control','style'=>"width: 3em"],
                'required'=>false])
            ->add('last',null,[
                'attr'=>['size'=>10,'placeholder'=>'Last','class'=>'form-control','style'=>"width: 8em"],
                'required'=>true,
                'constraints'=>[
                    new NotBlank([
                        'message'=>'Last name is required.'
                    ])]
            ])
            ->add('suffix',null,[
                'attr'=>['size'=>2,'placeholder'=>'Suffix','class'=>'form-control','style'=>"width: 4em"],
                'required'=>false,
                'mapped'=>false]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'=> Name::class
        ]);
    }

}