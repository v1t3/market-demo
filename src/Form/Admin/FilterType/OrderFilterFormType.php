<?php

declare(strict_types=1);

namespace App\Form\Admin\FilterType;

use App\Entity\StaticStorage\OrderStaticStorage;
use App\Entity\User;
use App\Form\DTO\EditOrderModel;
use Lexik\Bundle\FormFilterBundle\Filter\FilterOperands;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\ChoiceFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\DateTimeRangeFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\EntityFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\NumberFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\NumberRangeFilterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderFilterFormType extends AbstractType
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
            ->add('owner', EntityFilterType::class, [
                'label'        => 'Владелец',
                'class'        => User::class,
                'choice_label' => function ($user) {
                    return sprintf('#%s %s', $user->getId(), $user->getEmail());
                },
                'attr'         => [
                    'class' => 'form-control',
                ],
            ])
            ->add('status', ChoiceFilterType::class, [
                'label'   => 'Статус',
                'choices' => array_flip(OrderStaticStorage::getOrderStatusChoices()),
                'attr'    => [
                    'class' => 'form-control',
                ],
            ])
            ->add('totalPrice', NumberRangeFilterType::class, [
                'label'                => 'Общая цена',
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
                'data_class'        => EditOrderModel::class,
                'method'            => 'GET',
                'validation_groups' => ['filtering'],
            ]
        );
    }
}
