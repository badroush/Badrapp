<?php

namespace App\Form;

use App\Entity\Appel;
use App\Entity\Contact;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AppelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class, [
                'label' => 'نوع المكالمة',
                'choices' => [
                    'واردة' => 'entrant',
                    'صادرة' => 'sortant',
                ],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('dateAppel', null, [
                'label' => 'تاريخ المكالمة',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control', 'value' => date('Y-m-d')],
            ])
            ->add('heureAppel', null, [
                'label' => 'وقت المكالمة',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control', 'value' => date('H:i')],
            ])
            ->add('contactEmetteur', EntityType::class, [
                'class' => Contact::class,
                'choice_label' => 'nom',
                'label' => 'المرسل',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('contactRecepteur', EntityType::class, [
                'class' => Contact::class,
                'choice_label' => 'nom',
                'label' => 'المستقبل',
                'attr' => ['class' => 'form-control'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Appel::class,
        ]);
    }
}
