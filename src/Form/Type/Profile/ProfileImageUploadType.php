<?php

namespace App\Form\Type\Profile;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;

class ProfileImageUploadType extends AbstractType {
    /**
     * Build form.
     *
     * @param FormBuilderInterface $builder Form builder.
     * @param array $options Options.
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('image', FileType::class, [
                'label' => 'profile.form.label',
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '10M',
                        'mimeTypes' => ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'],
                        'mimeTypesMessage' => 'Please upload a valid image (JPG, PNG, GIF).',
                    ]),
                ],
                'attr' => [
                    'data-profile-target' => 'fileInput',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'profile.form.submit',
                'attr' => [
                    'data-action' => 'click->profile#submit',
                    'class' => 'btn',
                ],
            ])
            ->add('close', ButtonType::class, [
                'label' => 'profile.form.close',
                'attr' => [
                    'data-action' => 'click->profile#close',
                    'class' => 'btn',
                ],
            ]);
    }
}
