<?php

namespace App\Form\Type\Games;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class NewMapType extends AbstractType {
    /**
     * Build the form.
     *
     * @param FormBuilderInterface $builder Form builder.
     * @param array $options Form options.
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('name', TextType::class, [
                'label' => 'games.maps.form.map_name',
                'required' => true,
                'attr' => [
                    'data-maps-target' => 'mapName',
                    'id' => 'title',
                ],
            ])
            ->add('description', TextType::class, [
                'label' => 'games.maps.form.description',
                'required' => false,
                'attr' => [
                    'data-maps-target' => 'content',
                    'id' => 'content',
                ],
            ])
            ->add('image', FileType::class, [
                'label' => 'games.maps.form.image',
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '10M',
                        'mimeTypes' => ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'],
                        'mimeTypesMessage' => 'Please upload a valid image (JPG, PNG, GIF).',
                    ]),
                ],
                'attr' => [
                    'data-maps-target' => 'fileInput',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'games.maps.form.submit',
                'attr' => [
                    'data-action' => 'click->maps#submit',
                    'class' => 'btn',
                ],
            ])
            ->add('close', ButtonType::class, [
                'label' => 'games.maps.form.close',
                'attr' => [
                    'data-action' => 'click->maps#hide',
                    'class' => 'btn',
                ],
            ]);
    }

    /**
     * Configure options.
     *
     * @param OptionsResolver $resolver Options resolver.
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([]);
    }
}
