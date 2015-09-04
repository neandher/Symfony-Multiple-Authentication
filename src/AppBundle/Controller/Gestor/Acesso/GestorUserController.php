<?php

namespace AppBundle\Controller\Gestor\Acesso;

use AppBundle\Entity\Gestor\Acesso\GestorUser;
use AppBundle\Form\Type\Gestor\Acesso\GestorUserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/user")
 */
class GestorUserController extends Controller
{

    /**
     * @Route("/", name="gestor_user_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('gestor/acesso/gestorUser/index.html.twig');
    }

    /**
     * @Route("/new", name="gestor_user_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $entity = new GestorUser();

        $form = $this->createForm(new GestorUserType(), $entity);

        $formHandler = $this->get('app.gestor_user_form_handler');

        if ($formHandler->create($form, $request)) {
            return $this->redirectToRoute('gestor_user_index');
        }

        return $this->render('gestor/acesso/gestorUser/new.html.twig', array('entity' => $entity, 'form' => $form->createView()));
    }
}
