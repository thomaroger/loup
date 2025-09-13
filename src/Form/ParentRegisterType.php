<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ParentRegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email du parent',
                'attr' => [
                    'placeholder' => 'ex : exemple@email.com',
                    'maxlength' => 180,
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'L\'email est obligatoire',
                    ]),
                    new Email([
                        'message' => 'L\'email n\'est pas valide',
                    ]),
                    new Length([
                        'max' => 180,
                        'maxMessage' => 'L\'email ne peut pas dépasser {{ limit }} caractères',
                    ]),
                ],
            ])
            ->add('name', TextType::class, [
                'label' => 'Prénom et nom du parent',
                'attr' => [
                    'placeholder' => 'ex : Aurelie ROGER',
                    'maxlength' => 100,
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le nom est obligatoire',
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 100,
                        'minMessage' => 'Le nom doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères',
                    ]),
                    new Regex([
                        'pattern' => '/^[a-zA-ZÀ-ÿ\s\-\']+$/',
                        'message' => 'Le nom ne peut contenir que des lettres, espaces, tirets et apostrophes',
                    ]),
                ],
            ])
            ->add('childFirstName', TextType::class, [
                'mapped' => false,
                'label' => "Prénom de l'enfant",
                'attr' => [
                    'placeholder' => 'ex : Loup',
                    'maxlength' => 100,
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le prénom de l\'enfant est obligatoire',
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 100,
                        'minMessage' => 'Le prénom doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'Le prénom ne peut pas dépasser {{ limit }} caractères',
                    ]),
                    new Regex([
                        'pattern' => '/^[a-zA-ZÀ-ÿ\s\-\']+$/',
                        'message' => 'Le prénom ne peut contenir que des lettres, espaces, tirets et apostrophes',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
