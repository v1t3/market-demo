<?php

declare(strict_types=1);

namespace App\Form\Admin\FilterType;

use App\Entity\Category;
use App\Form\DTO\EditProductModel;
use App\Repository\CategoryRepository;
use Lexik\Bundle\FormFilterBundle\Filter\FilterOperands;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\BooleanFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\DateTimeRangeFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\EntityFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\NumberFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\NumberRangeFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\TextFilterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductFilterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', NumberFilterType::class, [
                'label' => 'Id',
                'attr'  => [
                    'class' => 'form-control',
                ],
            ])
            ->add('category', EntityFilterType::class, [
                'label'         => 'Категория',
                'class'         => Category::class,
                'query_builder' => function ($category) {
                    /** @var CategoryRepository $categoryRepo */
                    $categoryRepo = $category;

                    return $categoryRepo->forFormQueryBuilderFindActiveCategory();
                },
                'choice_label'  => function ($category) {
                    return sprintf('#%s %s', $category->getId(), $category->getTitle());
                },
                'attr'          => [
                    'class' => 'form-control',
                ],
            ])
            ->add('title', TextFilterType::class, [
                'label' => 'Название',
                'attr'  => [
                    'class' => 'form-control',
                ],
            ])
            ->add('price', NumberRangeFilterType::class, [
                'label'                => 'Цена',
                'left_number_options'  => [
                    'label'              => 'От',
                    'condition_operator' => FilterOperands::OPERATOR_GREATER_THAN_EQUAL,
                    'attr'               => [
                        'class' => 'form-control',
                    ],
                ],
                'right_number_options' => [
                    'label'              => 'До',
                    'condition_operator' => FilterOperands::OPERATOR_LOWER_THAN_EQUAL,
                    'attr'               => [
                        'class' => 'form-control',
                    ],
                ],
            ])
            ->add('quantity', NumberRangeFilterType::class, [
                'label'                => 'Количество',
                'left_number_options'  => [
                    'label'              => 'От',
                    'condition_operator' => FilterOperands::OPERATOR_GREATER_THAN_EQUAL,
                    'attr'               => [
                        'class' => 'form-control',
                    ],
                ],
                'right_number_options' => [
                    'label'              => 'До',
                    'condition_operator' => FilterOperands::OPERATOR_LOWER_THAN_EQUAL,
                    'attr'               => [
                        'class' => 'form-control',
                    ],
                ],
            ])
            ->add('createdAt', DateTimeRangeFilterType::class, [
                'label'                  => 'Дата создания',
                'left_datetime_options'  => [
                    'label'  => 'От',
                    'widget' => 'single_text',
                    'attr'   => [
                        'class' => 'form-control',
                    ],
                ],
                'right_datetime_options' => [
                    'label'  => 'До',
                    'widget' => 'single_text',
                    'attr'   => [
                        'class' => 'form-control',
                    ],
                ],
            ])
            ->add('isPublished', BooleanFilterType::class, [
                'label' => 'Опубликовано',
                'attr'  => [
                    'class' => 'form-control',
                ],
            ]);
    }

    public function getBlockPrefix(): string
    {
        return 'order_filter_form';
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class'        => EditProductModel::class,
                'method'            => 'GET',
                'validation_groups' => ['filtering'],
            ]
        );
    }
}
