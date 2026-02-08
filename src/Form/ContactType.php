<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', null, [
                'label' => 'الاسم',
                'attr' => ['autofocus' => true,'class' => 'form-control'],
            ])
            ->add('numero', null, [
                'label' => 'الرقم',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('fax', null, [
                'label' => 'الفاكس',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('email', null, [
                'label' => 'البريد الإلكتروني',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('adresse', null, [
                'label' => 'العنوان',
                'attr' => ['class' => 'form-control'],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
