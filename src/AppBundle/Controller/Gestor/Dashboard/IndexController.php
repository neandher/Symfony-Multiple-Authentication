<?php

namespace AppBundle\Controller\Gestor\Dashboard;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IndexController extends Controller
{
    /**
     * @Route("/", name="gestor_dashboard")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('gestor/dashboard/index.html.twig');
    }
}
