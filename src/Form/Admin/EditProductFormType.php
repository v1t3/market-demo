<?php

declare(strict_types=1);

namespace App\Form\Admin;

use App\Entity\Category;
use App\Form\DTO\EditProductModel;
use App\Form\Validator\GreaterThanOrEqualPrice;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditProductFormType extends AbstractType
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
                    new NotBlank(message: 'Не может быть пустым'),
                ],
            ])
            ->add('price', NumberType::class, [
                'label'       => 'Цена',
                'required'    => true,
                'scale'       => 2,
                'html5'       => true,
                'attr'        => [
                    'class' => 'form-control',
                    'min'   => 0,
                ],
                'constraints' => [
                    new NotBlank(message: 'Укажите цену'),
                    new GreaterThanOrEqualPrice(),
                ],
            ])
            ->add('quantity', IntegerType::class, [
                'label'       => 'Количество',
                'required'    => true,
                'attr'        => [
                    'class' => 'form-control',
                    'min'   => 0,
                ],
                'constraints' => [
                    new NotBlank(message: 'Укажите количество'),
                ],
            ])
            ->add('description', TextareaType::class, [
                'label'    => 'Описание',
                'required' => true,
                'attr'     => [
                    'class' => 'form-control',
                    'style' => 'overflow: hidden',
                ],
            ])
            ->add('newImage', FileType::class, [
                'label'       => 'Выбрать новое изображение',
                'required'    => false,
                'attr'        => [
                    'class' => 'form-control-file',
                ],
                'constraints' => [
                    new File(
                        maxSize:          '10M',
                        mimeTypes:        ['image/jpeg', 'image/png'],
                        mimeTypesMessage: 'Пожалуйста загрузите корректное изображение (*.jpg or *.png)'
                    ),
                ],
            ])
            ->add('isPublished', CheckboxType::class, [
                'label'      => 'Опубликовано',
                'required'   => false,
                'attr'       => [
                    'class' => 'form-check-input',
                ],
                'label_attr' => [
                    'class' => 'form-check-label',
                ],
            ])
            ->add('category', EntityType::class, [
                'label'         => 'Категория',
                'required'      => true,
                'class'         => Category::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er
                        ->createQueryBuilder('c')
                        ->where('c.isDeleted != true');
                },
                'choice_label'  => 'title',
                'placeholder'   => 'Укажите категорию',
                'attr'          => [
                    'class' => 'form-control',
                ],
                'constraints'   => [
                    new NotBlank(message: 'Укажите заголовок'),
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
                'data_class' => EditProductModel::class,
            ]
        );
    }
}
