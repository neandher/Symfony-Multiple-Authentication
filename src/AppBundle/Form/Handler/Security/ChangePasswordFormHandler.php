<?php

namespace AppBundle\Form\Handler\Security;

use AppBundle\DomainManager\UserManagerInterface;
use AppBundle\Event\FlashBag\FlashBagEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\Translation\Translator;

class ChangePasswordFormHandler
{

    /**
     * @var UserManagerInterface
     */
    private $userManager;

    /**
     * @var FlashBag
     */
    private $flashBag;

    /**
     * @var Translator
     */
    private $translator;

    public function __construct(UserManagerInterface $userManager, FlashBag $flashBag, Translator $translator)
    {
        $this->userManager = $userManager;
        $this->flashBag = $flashBag;
        $this->translator = $translator;
    }

    public function handle(FormInterface $form, Request $request)
    {
        $form->handleRequest($request);

        if (!$form->isValid()) {
            return false;
        }

        $entity = $form->getData();

        $entity->setPassword(null);

        $this->userManager->changePassword($entity);

        $this->flashBag->add(
            FlashBagEvents::MESSAGE_TYPE_SUCCESS,
            $this->translator->trans('security.change_password.success')
        );

        return true;
    }
}