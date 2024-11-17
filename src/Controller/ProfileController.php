<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\Front\ProfileEditFormType;
use App\Security\Verifier\EmailVerifier;
use App\Utils\Mailer\Sender\UserRegisteredEmailSender;
use Doctrine\Persistence\ManagerRegistry as Doctrine;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Service\Attribute\Required;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 *
 */
class ProfileController extends AbstractController
{
    /**
     * @var Doctrine
     */
    private Doctrine $doctrine;

    /**
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;

    /**
     * @param Doctrine $doctrine
     *
     * @return $this
     */
    #[Required]
    public function setDoctrine(Doctrine $doctrine): ProfileController
    {
        $this->doctrine = $doctrine;

        return $this;
    }

    /**
     * @var EmailVerifier
     */
    private EmailVerifier $emailVerifier;

    /**
     * @param EmailVerifier $emailVerifier
     *
     * @return $this
     */
    #[Required]
    public function setEmailVerifier(EmailVerifier $emailVerifier): ProfileController
    {
        $this->emailVerifier = $emailVerifier;

        return $this;
    }

    /**
     * @var UserRegisteredEmailSender
     */
    private UserRegisteredEmailSender $emailSender;

    /**
     * @param UserRegisteredEmailSender $emailSender
     *
     * @return $this
     */
    #[Required]
    public function setEmailSender(UserRegisteredEmailSender $emailSender): ProfileController
    {
        $this->emailSender = $emailSender;

        return $this;
    }

    /**
     * @param TranslatorInterface $translator
     *
     * @return $this
     */
    #[Required]
    public function setTranslator(TranslatorInterface $translator): ProfileController
    {
        $this->translator = $translator;

        return $this;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    #[Route('/profile', name: 'main_profile_index')]
    public function index(Request $request): Response
    {
        $sendEmail = false;

        if ($request->getSession()->get('resending_verify_email_link')) {
            $sendEmail = true;
            $request->getSession()->remove('resending_verify_email_link');
        }

        return $this->render('front/profile/index.html.twig', [
            'sendEmail' => $sendEmail,
        ]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    #[Route('/profile/edit', name: 'main_profile_edit')]
    public function edit(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfileEditFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->doctrine->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('main_profile_index');
        }

        return $this->render('front/profile/edit.html.twig', [
            'profileEditForm' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    #[Route('/profile/resending-verify-email-link', name: 'main_profile_resending_verify_email_link')]
    public function resendingVerifyEmailLink(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $isVerified = $user->isVerified();

        if (!$isVerified) {
            $verifyEmailLink = $this
                ->emailVerifier
                ->generateEmailSignature('main_verify_email', $user);
            $this->emailSender->sendEmailToClient($user, $verifyEmailLink);
        }

        $request->getSession()->set('resending_verify_email_link', true);

        return $this->redirectToRoute('main_profile_index');
    }
}
