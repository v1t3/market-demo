<?php

declare(strict_types=1);

namespace App\Form\Admin;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditArticleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('active', CheckboxType::class, [
                'label'    => 'Активность',
                'required' => false,
            ])
            ->add('title', TextType::class, [
                'label'       => 'Название',
                'required'    => true,
                'trim'        => true,
                'attr'        => [
                    'class' => 'form-control col-md-4',
                ],
                'constraints' => [
                    new NotBlank(message: 'Should be filled'),
                ],
            ])
            ->add('code', TextType::class, [
                'label'       => 'Символьный код',
                'required'    => true,
                'trim'        => true,
                'attr'        => [
                    'class' => 'form-control col-md-4',
                ],
                'constraints' => [
                    new NotBlank(message: 'Should be filled'),
                ],
            ])
            ->add('preview', TextareaType::class, [
                'label'    => 'Описание анонса',
                'required' => false,
                'attr'     => [
                    'class' => 'form-control col-sm-6',
                    'rows'  => 5
                ],
            ])
            ->add('description', TextareaType::class, [
                'label'    => 'Детальное описание',
                'required' => false,
                'attr'     => [
                    'class' => 'form-control col-sm-6 ',
                    'rows'  => 9
                ],
            ])
            ->add('previewImageFile', FileType::class, [
                'label'       => 'Фото анонса',
                'mapped'      => false,
                'required'    => false,
                'attr'        => [
                    'class' => 'custom-file-input',
                    'id'    => 'validatedPreviewFile',
                    'label' => 'Открыть',
                ],
                'constraints' => [
                    new File(
                        [
                            'maxSize'          => '1024k',
                            'mimeTypes'        => [
                                'image/jpeg',
                                'image/png',
                                'image/svg+xml',
                                'image/webp',
                                'image/gif',
                            ],
                            'mimeTypesMessage' => 'Please upload a valid image',
                        ]
                    )
                ],
            ])
            ->add('detailImageFile', FileType::class, [
                'label'       => 'Детальное фото',
                'mapped'      => false,
                'required'    => false,
                'constraints' => [
                    new File(
                        [
                            'maxSize'          => '1024k',
                            'mimeTypes'        => [
                                'image/jpeg',
                                'image/png',
                                'image/svg+xml',
                                'image/webp',
                                'image/gif',
                            ],
                            'mimeTypesMessage' => 'Please upload a valid image',
                        ]
                    )
                ],
            ])
            ->add('activeFrom', DateTimeType::class, [
                'label'    => 'Дата начала активности',
                'required' => false,
                'attr'     => [
                    'class' => 'form-control col-md-2',
                ],
                'widget'   => 'single_text',
            ])
            ->add('activeTo', DateTimeType::class, [
                'label'    => 'Дата окончания активности',
                'required' => false,
                'attr'     => [
                    'class' => 'form-control col-md-2',
                ],
                'widget'   => 'single_text',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Сохранить изменения'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Article::class
            ]
        );
    }
}
