<?php

namespace App\Form;

use App\Request\CreateUserRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Username',
                'required' => true,
                'attr' => [
                    'id' => 'username',
                ],
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Firstname',
                'required' => true,
                'attr' => [
                    'id' => 'firstname',
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Lastname',
                'required' => true,
                'attr' => [
                    'id' => 'lastname',
                ],
            ])
            ->add('password', TextType::class, [
                'label' => 'Password',
                'required' => true,
                'attr' => [
                    'id' => 'password',
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
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
            'data_class' => CreateUserRequest::class,
        ]);
    }
}
