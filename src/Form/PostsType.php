<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\Posts;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class PostsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Entrez le titre',
                    'rows' => 2
                ]
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Image (JPEG ou PNG)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '15M',
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Veuillez uploader une image au format JPEG ou PNG',
                    ])
                ],
            ])
            ->add('description', null, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Entrez la description',
                ]
            ])
            ->add('content', null, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Entrez le contenu',
                ]
            ])
            ->add('category', EntityType::class, [
                'class' => Categories::class,
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Posts::class,
        ]);
    }
}
