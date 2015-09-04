<?php

namespace AppBundle\EventListener\Security;

use AppBundle\Event\FlashBag\FlashBagEvents;
use AppBundle\Event\Security\UserEvent;
use AppBundle\Event\Security\UserEvents;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Translation\Translator;

class ResettingRequestSendEmailSubscriber implements EventSubscriberInterface
{

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var Translator
     */
    private $translator;

    /**
     * @var TwigEngine
     */
    private $twigEngine;

    /**
     * @var UrlGeneratorInterface
     */
    private $router;

    /**
     * @var FlashBag
     */
    private $flashBag;

    /**
     * @var ParameterBag
     */
    private $parameter;

    public function __construct(
        \Swift_Mailer $mailer,
        Translator $translator,
        TwigEngine $twigEngine,
        UrlGeneratorInterface $router,
        FlashBag $flashBag,
        ParameterBag $parameter
    ) {
        $this->mailer = $mailer;
        $this->translator = $translator;
        $this->twigEngine = $twigEngine;
        $this->router = $router;
        $this->flashBag = $flashBag;
        $this->parameter = $parameter;
    }

    /**
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return array(
            UserEvents::RESETTING_REQUEST_SUCCESS => 'onResettingRequestSuccess'
        );
    }

    /**
     * @param UserEvent $event
     * @throws \Exception
     * @throws \Twig_Error
     */
    public function onResettingRequestSuccess(UserEvent $event)
    {

        $user = $event->getUser();
        $params = $event->getParams();

        $url = $this->router->generate(
            $params[$event::PARAM_RESETTING_EMAIL_ROUTE],
            array('token' => $user->getConfirmationToken()),
            true
        );

        $message = \Swift_Message::newInstance()
            ->setSubject($this->translator->trans('security.resetting.request.email.subject'))
            ->setFrom($this->parameter->get($params[$event::PARAM_RESETTING_EMAIL_FROM]))
            ->setTo($user->getEmail())
            ->setBody(
                $this->twigEngine->render(
                    $params[$event::PARAM_RESETTING_EMAIL_TEMPLATE],
                    array('complete_name' => $event->getSecureArea()->getCompleteName(), 'url' => $url)
                )
            );

        $this->mailer->send($message);

        $this->flashBag->add(
            FlashBagEvents::MESSAGE_TYPE_SUCCESS,
            $this->translator->trans(
                'security.resetting.request.check_email',
                array('user_email' => $user->getObfuscatedEmail())
            )
        );
    }

}