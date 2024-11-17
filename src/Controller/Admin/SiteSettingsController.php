<?php

namespace App\Controller\Admin;

use App\Entity\SiteSettings;
use App\Repository\SiteSettingsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 *
 */
class SiteSettingsController extends AbstractController
{
    /**
     * @return Response
     */
    #[Route('/admin/settings', name: 'app_site_settings')]
    public function index(): Response
    {
        return $this->redirectToRoute('app_site_settings_site');
    }

    /**
     * @param Request                $request
     * @param TranslatorInterface    $translator
     * @param SiteSettingsRepository $repository
     * @param EntityManagerInterface $em
     * @param SluggerInterface       $slugger
     *
     * @return Response
     */
    #[Route('/admin/settings/site', name: 'app_site_settings_site')]
    public function edit(
        Request $request,
        TranslatorInterface $translator,
        SiteSettingsRepository $repository,
        EntityManagerInterface $em,
        SluggerInterface $slugger,
    ): Response {
        $formBuilder = $this->createFormBuilder();
        $formBuilder->add('active', CheckboxType::class, [
            'label'    => $translator->trans('site_settings.fields.active'),
            'required' => false,
        ]);
        $formBuilder->add('name', TextType::class, [
            'label'    => $translator->trans('site_settings.fields.name'),
            'required' => false,
        ]);
        $formBuilder->add('logo', FileType::class, [
            'label'       => $translator->trans('site_settings.fields.logo'),
            'mapped'      => false,
            'required'    => false,
            // unmapped fields can't define their validation using attributes
            // in the associated entity, so you can use the PHP constraint classes
            'constraints' => [
                new File(
                    [
                        'maxSize'          => '1024k',
                        'mimeTypes'        => [
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                            'image/svg+xml',
                        ],
                        'mimeTypesMessage' => 'Загрузить изображение',
                    ]
                )
            ],
        ]);
        $formBuilder->add('logoFilename', TextType::class, [
            'required' => false,
        ]);
        $formBuilder->add('domain', TextType::class, [
            'label'    => $translator->trans('site_settings.fields.domain'),
            'required' => false,
        ]);
        $formBuilder->add('save', SubmitType::class, [
            'label' => $translator->trans('site_settings.form.save')
        ]);

        //todo favicon
        //todo locale
        //todo admin email

        $form = $formBuilder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $logoFile */
            $logoFile = $form->get('logo')->getData();

            $settings = new SiteSettings();
            if ($obj = $repository->findAll()[0] ?? null) {
                $settings = $obj;
            }

            $settings->setActive($form->get('active')->getData());
            $settings->setName((string)$form->get('name')->getData());
            $settings->setDomain((string)$form->get('domain')->getData());

            if ($logoFile) {
                if ($settings->getLogoFilename()) {
                    unlink($this->getParameter('uploads_images_dir') . '/' . $settings->getLogoFilename());
                }

                $originalFilename = pathinfo($logoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $logoFile->guessExtension();
                $logoFile->move($this->getParameter('uploads_images_dir'), $newFilename);

                $settings->setLogoFilename($newFilename);
            }

            $em->persist($settings);
            $em->flush();

            return $this->redirectToRoute('app_site_settings_site');
        }

        $settings = $repository->findAll()[0] ?? null;

        if ($settings) {
            $formBuilder->get('active')->setData($settings->isActive());
            $formBuilder->get('name')->setData($settings->getName());
            $formBuilder->get('domain')->setData($settings->getDomain());

            $formBuilder->get('logoFilename')->setData($settings->getLogoFilename());
            if ($settings->getLogoFilename()) {
                $formBuilder->get('logo')->setData(
                    new \Symfony\Component\HttpFoundation\File\File(
                        $this->getParameter('uploads_images_dir') . '/' . $settings->getLogoFilename()
                    )
                );
            }
        }

        return $this->render('admin/settings/site.html.twig', [
            'form'      => $formBuilder->getForm(),
            'logoImage' => $this->getParameter('uploads_images_url') . '/' . $settings?->getLogoFilename()
        ]);
    }
}
