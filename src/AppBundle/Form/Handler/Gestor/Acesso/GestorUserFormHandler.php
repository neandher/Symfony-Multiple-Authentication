<?php

namespace AppBundle\Form\Handler\Gestor\Acesso;

use AppBundle\DomainManager\Gestor\Acesso\GestorUserManager;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class GestorUserFormHandler
{

    /**
     * @var GestorUserManager
     */
    private $manager;

    public function __construct(GestorUserManager $manager)
    {
        $this->manager = $manager;
    }

    public function create(FormInterface $form, Request $request)
    {
        $form->handleRequest($request);

        if (!$form->isValid()) {
            return false;
        }

        $entity = $form->getData();

        if ($form->isSubmitted()) {
            if (!is_null($this->manager->checkUniqueEmail($entity->getUser()->getEmail()))) {

                $form->addError(new FormError('Email ja cadastrado!'));

                return false;
            }
        }

        $this->manager->create($entity);

        return true;
    }
}
