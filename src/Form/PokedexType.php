<?php

namespace App\Form;

use App\Entity\Pokedex;
use App\Repository\PokedexRepository;
use phpDocumentor\Reflection\PseudoTypes\Numeric_;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;

class PokedexType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nombre',
                'required' => true,
            ])

            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Agua' => 'water',
                    'Fuego' => 'fire',
                    'Planta' => 'grass',
                    'Tierra' => 'ground',
                    'Volador' => 'flying',
                    'Psíquico' => 'psychic',
                    'Hielo' => 'ice',
                    'Eléctrico' => 'electric',
                ],
                'label' => 'Tipo:',
                'multiple' => true,
                'expanded' => true,
                'required' => true
            ])

            ->add('image', FileType::class, [
                'label' => 'Imagen',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '4096k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                            'image/webp'
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image file (JPEG, PNG, GIF, WEBP)',
                    ])
                ],
            ])
            ->add('evolutionLevel', IntegerType::class, [
                'label' => 'Nivel de evolución',
                'required' => false,
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value' => 0,
                        'message' => 'Nivel de evolución debe ser mayor o igual a 0.'
                    ]),
                    new LessThanOrEqual([
                        'value' => 100,
                        'message' => 'Nivel de evolución debe ser menor o igual a 100.'
                    ])
                ]
            ])
            ->add('evolution', EntityType::class, [
                'class' => Pokedex::class,
                'choice_label' => 'name',
                'placeholder' => 'Select evolution',
                'required' => false,
                'query_builder' => function (PokedexRepository $er) use ($options) {
                    $qb = $er->createQueryBuilder('p');

                    // Only exclude current Pokemon if we're editing an existing one
                    if ($options['data'] && $options['data']->getId()) {
                        $qb->where('p.id != :currentId')
                            ->setParameter('currentId', $options['data']->getId());
                    }

                    return $qb->orderBy('p.name', 'ASC');
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pokedex::class,
        ]);
    }
}
