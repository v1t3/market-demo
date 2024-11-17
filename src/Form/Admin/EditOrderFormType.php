<?php

declare(strict_types=1);

namespace App\Form\Admin;

use App\Entity\StaticStorage\OrderStaticStorage;
use App\Entity\User;
use App\Form\DTO\EditOrderModel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditOrderFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('status', ChoiceType::class, [
                'label'       => 'Статус',
                'required'    => false,
                'choices'     => array_flip(OrderStaticStorage::getOrderStatusChoices()),
                'attr'        => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank(message: 'Укажите статус'),
                ],
            ])
            ->add('owner', EntityType::class, [
                'label'        => 'Пользователь',
                'class'        => User::class,
                'required'     => false,
                'choice_label' => static function (User $user) {
                    return sprintf(
                        '#%s / %s / %s',
                        $user->getId(),
                        $user->getFullName(),
                        $user->getEmail(),
                    );
                },
                'attr'         => [
                    'class' => 'form-control',
                ],
                'constraints'  => [
                    new NotBlank(message: 'Укажите пользователя'),
                ],
            ])
            ->add('isDeleted', CheckboxType::class, [
                'label'      => 'Удалено',
                'required'   => false,
                'attr'       => [
                    'class' => 'form-check-input',
                ],
                'label_attr' => [
                    'class' => 'form-check-label',
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
                'data_class' => EditOrderModel::class,
            ]
        );
    }
}
