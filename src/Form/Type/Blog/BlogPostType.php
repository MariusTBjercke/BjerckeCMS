<?php

namespace App\Form\Type\Blog;

use App\Request\NewBlogPostRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlogPostType extends AbstractType {
    /**
     * Build the form.
     *
     * @param FormBuilderInterface $builder Form builder.
     * @param array $options Form options.
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Title',
                'required' => true,
                'attr' => [
                    'data-blog-target' => 'title',
                    'id' => 'title',
                ],
            ])
            ->add('content', TextType::class, [
                'label' => 'Content',
                'required' => false,
                'attr' => [
                    'data-blog-target' => 'content',
                    'id' => 'content',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Submit',
                'attr' => [
                    'data-action' => 'click->blog#submit',
                    'class' => 'btn',
                ],
            ])
            ->add('close', ButtonType::class, [
                'label' => 'Close',
                'attr' => [
                    'data-action' => 'click->blog#close',
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
        $resolver->setDefaults([
            'data_class' => NewBlogPostRequest::class,
        ]);
    }
}
