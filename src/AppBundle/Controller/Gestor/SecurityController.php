<?php

namespace AppBundle\Controller\Gestor;

use AppBundle\Controller\SecurityControllerInterface;


use AppBundle\Entity\User;
use AppBundle\Event\Security\UserEvent;
use AppBundle\Event\Security\UserEvents;
use AppBundle\Form\Type\Security\ChangePasswordType;
use AppBundle\Form\Type\Security\LoginType;
use AppBundle\Form\Type\Security\ResettingRequestType;
use AppBundle\Form\Type\Security\ResettingResetType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller implements SecurityControllerInterface
{

    /**
     * @Route("/login", name="gestor_security_login")
     * @Method("GET")
     */
    public function loginAction()
    {
        $form = $this->createForm(new LoginType());

        $helper = $this->get('security.authentication_utils');

        return $this->render(
            'gestor/security/login.html.twig',
            array(
                'form'          => $form->createView(),
                'last_username' => $helper->getLastUsername(),
                'error'         => $helper->getLastAuthenticationError(),
            )
        );
    }

    /**
     * @Route("/login_check", name="gestor_security_login_check")
     */
    public function loginCheckAction()
    {
    }

    /**
     * @Route("/logout", name="gestor_security_logout")
     */
    public function logoutAction()
    {
    }

    /**
     * @Route("/resetting/request", name="gestor_security_resetting_request")
     * @Method({"GET", "POST"})
     */
    public function resettingRequestAction(Request $request)
    {
        $form = $this->createForm(new ResettingRequestType());

        $formHandler = $this->get('app.gestor_resetting_request_form_handler');

        if ($formHandler->handle($form, $request)) {
            return $this->redirectToRoute('gestor_security_login');
        }

        return $this->render(
            'gestor/security/resetting/resettingRequest.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * @Route("/resetting/reset/{token}", name="gestor_security_resetting_reset")
     * @Method({"GET", "POST"})
     */
    public function resettingResetAction(Request $request, $token)
    {

        $userEvent = new UserEvent((new User())->setConfirmationToken($token));

        $userEvent->setManager($this->get('app.gestor_user_manager'));

        $this->get('event_dispatcher')->dispatch(UserEvents::RESETTING_RESET_INITIALIZE, $userEvent);

        if ($userEvent->getHasError()) {
            return $this->redirectToRoute('gestor_security_login');
        }

        $form = $this->createForm(new ResettingResetType(), $userEvent->getUser());

        $formHandler = $this->get('app.gestor_resetting_reset_form_handler');

        if ($formHandler->handle($form, $request)) {
            return $this->redirectToRoute('gestor_security_login');
        }

        return $this->render(
            'gestor/security/resetting/resettingReset.html.twig',
            array('form' => $form->createView(), 'token' => $token)
        );
    }

    /**
     * @Route("/change-password", name="gestor_security_change_password")
     * @Method({"GET", "POST"})
     */
    public function changePassword(Request $request)
    {
        $user = $this->getUser();

        $form = $this->createForm(new ChangePasswordType(), $user);

        $formHandler = $this->get('app.gestor_change_password_form_handler');

        if ($formHandler->handle($form, $request)) {
            return $this->redirectToRoute('gestor_security_change_password');
        }

        return $this->render(
            'gestor/security/changePassword/changePassword.html.twig',
            array('form' => $form->createView())
        );
    }
}
