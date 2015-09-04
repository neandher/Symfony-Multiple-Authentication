<?php

namespace AppBundle\Controller\Admin\Dashboard;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IndexController extends Controller
{
    /**
     * @Route("/", name="admin_dashboard")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('admin/dashboard/index.html.twig');
    }
}
