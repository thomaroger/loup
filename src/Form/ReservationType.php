<?php

declare(strict_types=1);

namespace App\Form;

use Doctrine\Persistence\ObjectRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $parent = $options['parent'];

        $builder
            ->add('child', EntityType::class, [
                'class' => \App\Entity\Child::class,
                'query_builder' => function (ObjectRepository $er) use ($parent) {
                    return $er->createQueryBuilder('c')
                        ->where('c.parent = :parent')
                        ->setParameter('parent', $parent)
                        ->orderBy('c.firstName', 'ASC');
                },
                'choice_label' => 'firstName',
                'placeholder' => 'Sélectionnez votre enfant',
            ])
            ->add('justification', TextareaType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ex: Anniversaire, sortie familiale...',
                    'maxlength' => 500,
                    'rows' => 3,
                ],
                'constraints' => [
                    new Length([
                        'max' => 500,
                        'maxMessage' => 'La justification ne peut pas dépasser {{ limit }} caractères',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => \App\Entity\Reservation::class,
            'parent' => null,
        ]);
    }
}
