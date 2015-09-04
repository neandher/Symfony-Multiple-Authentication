<?php

namespace AppBundle\EventListener\Security;

use AppBundle\Event\FlashBag\FlashBagEvents;
use AppBundle\Event\Security\UserEvent;
use AppBundle\Event\Security\UserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Translation\Translator;

class ResettingResetSubscriber implements EventSubscriberInterface
{

    /**
     * @var FlashBag
     */
    private $flashBag;

    /**
     * @var Translator
     */
    private $translator;

    /**
     * @var UrlGeneratorInterface
     */
    private $router;

    /**
     * @var int
     */
    private $tokenTll;

    public function __construct(
        FlashBag $flashBag,
        Translator $translator,
        UrlGeneratorInterface $router,
        $tokenTll
    ) {
        $this->flashBag = $flashBag;
        $this->translator = $translator;
        $this->router = $router;
        $this->tokenTll = $tokenTll;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return array(
            UserEvents::RESETTING_RESET_INITIALIZE => 'onResettingResetInitialize',
            UserEvents::RESETTING_RESET_SUCCESS    => 'onResettingResetSuccess',
        );
    }


    public function onResettingResetInitialize(UserEvent $event)
    {
        $token = $event->getUser()->getConfirmationToken();

        $user = $event->getManager()->findUserByConfirmationToken($token);

        if (is_null($user)) {

            $this->flashBag->add(
                FlashBagEvents::MESSAGE_TYPE_ERROR,
                $this->translator->trans('security.resetting.reset.errors.invalid_token')
            );

            $event->setHasError(true);

        } elseif (!$user->isPasswordRequestNonExpired($this->tokenTll)) {

            $this->flashBag->add(
                FlashBagEvents::MESSAGE_TYPE_ERROR,
                $this->translator->trans('security.resetting.reset.errors.expired_token')
            );

            $event->setHasError(true);

        } else {
            $user->setSalt($event->getUser()->getSalt());
            $event->setUser($user);
        }
    }

    public function onResettingResetSuccess(UserEvent $event)
    {
        $user = $event->getUser();

        $user->setPasswordRequestedAt(null)
            ->setConfirmationToken(null);
    }
}