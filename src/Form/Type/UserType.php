<?php

namespace App\Form\Type;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('username', TextType::class, [
                'label' => 'user.username',
                'required' => true,
                'constraints' => [new NotBlank(), new Length(['min' => 3])],
                'attr' => [
                    'id' => 'username',
                ],
            ])
            ->add('firstname', TextType::class, [
                'label' => 'user.firstname',
                'required' => true,
                'constraints' => [new NotBlank(), new Length(['min' => 1])],
                'attr' => [
                    'id' => 'firstname',
                ],
            ])
            ->add('user.lastname', TextType::class, [
                'label' => 'Lastname',
                'required' => true,
                'constraints' => [new NotBlank(), new Length(['min' => 1])],
                'attr' => [
                    'id' => 'lastname',
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'constraints' => [new NotBlank(), new Length(['min' => 6])],
                'first_options' => [
                    'label' => 'user.password',
                    'attr' => [
                        'id' => 'password',
                    ],
                ],
                'second_options' => [
                    'label' => 'user.repeat_password',
                    'attr' => [
                        'id' => 'password-confirm',
                    ],
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'user.email',
                'required' => true,
                'constraints' => [new NotBlank(), new Length(['min' => 3])],
                'attr' => [
                    'id' => 'email',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'user.register',
                'attr' => [
                    'class' => 'btn',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => User::class,
            'validation_groups' => ['registration'],
        ]);
    }
}
