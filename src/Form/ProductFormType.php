<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('price', IntegerType::class)
            ->add('slug', TextType::class)
            ->add('img', FileType::class, [
                'required' => false,

                'label' => 'produit',
                'attr' => [
                    'placeholder' => 'nom image'
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '40096k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],

                        'mimeTypesMessage' => 'Merci de charger une jpg/png',
                        'uploadFormSizeErrorMessage' => 'Taille maximale de fichier 4 Méga'
                    ])
                ]
            ])

            ->add(
                'category',
                EntityType::class,
                [
                    'class' => Category::class,
                    'choice_label' => 'Name',
                    'placeholder' => 'Choose a category',
                    'label' => 'Category',
                ]
            )
            ->add('save', SubmitType::class, ['label' => 'Go !']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,

        ]);
    }
}
