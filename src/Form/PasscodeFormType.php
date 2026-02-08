<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class PasscodeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('passcode', TextType::class, [
                'label' => 'Code de vérification',
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 6, 'max' => 6]),
                ],
            ])
        ;
    }
}