<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\Persistence\ObjectRepository;

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
            ->add('justification', TextareaType::class, ['required' => false]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => \App\Entity\Reservation::class,
            'parent' => null,
        ]);
    }
}