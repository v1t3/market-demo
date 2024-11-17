<?php

declare(strict_types=1);

namespace App\Form\Admin\FilterType;

use App\Entity\Article;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\DateTimeRangeFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\NumberFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\TextFilterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleFilterFormType extends AbstractType
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
            ->add('title', TextFilterType::class, [
                'label' => 'Название',
                'attr'  => [
                    'class' => 'form-control',
                ],
            ])
            ->add('code', TextFilterType::class, [
                'label' => 'Символьный код',
                'attr'  => [
                    'class' => 'form-control',
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
                'data_class'        => Article::class,
                'method'            => 'GET',
                'validation_groups' => ['filtering'],
            ]
        );
    }
}
