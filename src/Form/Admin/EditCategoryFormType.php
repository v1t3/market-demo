<?php

declare(strict_types=1);

namespace App\Form\Admin;

use App\Form\DTO\EditCategoryModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditCategoryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label'       => 'Название',
                'required'    => true,
                'trim'        => true,
                'attr'        => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank(message: 'Укажите название'),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Сохранить',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => EditCategoryModel::class,
            ]
        );
    }
}
