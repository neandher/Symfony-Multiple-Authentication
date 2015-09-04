<?php

namespace AppBundle\Form\Handler\Security;

use AppBundle\DomainManager\UserManagerInterface;
use AppBundle\Entity\User;
use AppBundle\Helper\TokenGeneratorHelper;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\Translator;

class ResettingRequestFormHandler
{

    /**
     * @var UserManagerInterface
     */
    private $userManager;

    /**
     * @var TokenGenerator
     */
    private $tokenGenerator;

    /**
     * @var Translator
     */
    private $translator;

    private $tokenTll;

    public function __construct(
        UserManagerInterface $userManager,
        TokenGeneratorHelper $tokenGenerator,
        Translator $translator,
        $tokenTll
    ) {
        $this->userManager = $userManager;
        $this->tokenGenerator = $tokenGenerator;
        $this->tokenTll = $tokenTll;
        $this->translator = $translator;
    }

    public function handle(FormInterface $form, Request $request)
    {
        $form->handleRequest($request);

        if (!$form->isValid()) {
            return false;
        }

        $data = $form->getData();

        if ($form->isSubmitted()) {

            $user = $this->userManager->findUserByEmail($data['email']);

            if (is_null($user)) {
                $form->addError(
                    new FormError($this->translator->trans('security.resetting.request.errors.email_not_found'))
                );

                return false;
            }

            if ($user->isPasswordRequestNonExpired($this->tokenTll)) {
                $form->addError(
                    new FormError(
                        $this->translator->trans('security.resetting.request.errors.password_already_requested')
                    )
                );

                return false;
            }

            if ($user->getConfirmationToken() === null) {
                $user->setConfirmationToken($this->tokenGenerator->generateToken());
            }

            $user->setPasswordRequestedAt(new \DateTime());

            $this->userManager->resettingRequest($user);
        }

        return true;
    }
}