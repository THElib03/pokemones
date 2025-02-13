<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class HuntType extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options): void{
        $builder
            -> add('flee', SubmitType::class, ['label' => 'Huye!'])
            -> add('hunt', SubmitType::class, ['label' => 'Atrápalo!']);
    }


}