<?php

namespace App\Form\Type;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
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
                'label' => 'Username',
                'required' => true,
                'constraints' => [new NotBlank(), new Length(['min' => 3])],
                'attr' => [
                    'id' => 'username',
                ],
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Firstname',
                'required' => true,
                'constraints' => [new NotBlank(), new Length(['min' => 1])],
                'attr' => [
                    'id' => 'firstname',
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Lastname',
                'required' => true,
                'constraints' => [new NotBlank(), new Length(['min' => 1])],
                'attr' => [
                    'id' => 'lastname',
                ],
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Password',
                'required' => true,
                'constraints' => [new NotBlank(), new Length(['min' => 3])],
                'attr' => [
                    'id' => 'password',
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
                'constraints' => [new NotBlank(), new Length(['min' => 3])],
                'attr' => [
                    'id' => 'email',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Register',
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