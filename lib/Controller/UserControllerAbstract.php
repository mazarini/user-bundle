<?php

/*
 * Copyright (C) 2019-2020 Mazarini <mazarini@protonmail.com>.
 * This file is part of mazarini/user-bundle.
 *
 * mazarini/user-bundle is free software: you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or (at your
 * option) any later version.
 *
 * mazarini/user-bundle is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 * or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for
 * more details.
 *
 * You should have received a copy of the GNU General Public License
 */

namespace Mazarini\UserBundle\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Form\UserType;
use Mazarini\CrudBundle\Controller\AbstractCrudController;
use Mazarini\ToolsBundle\Entity\EntityInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class UserControllerAbstract extends AbstractCrudController
{
    /**
     * @var UserPasswordEncoderInterface
     */
    protected $encoder;

    public function changePasswordAction(Request $request, UserPasswordEncoderInterface $encoder, User $entity): Response
    {
        $this->encoder = $encoder;
        $form = $this->container
            ->get('form.factory')
            ->createNamed('Entity', ChangePasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entity->setPassword($this->encoder->encodePassword($entity, $form->get('password')->getData()));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            $this->addFlash('info', 'Password changed');

            return $this->redirect($this->data->generateUrl('_show', ['id' => $entity->getId()]));
        }

        $this->data->setEntity($entity);

        return $this->dataRender('change_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function new(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $this->encoder = $encoder;

        return $this->editAction($request, new User(), UserType::class);
    }

    /**
     * valid.
     */
    protected function valid(EntityInterface $entity, Form $form): bool
    {
        if (!is_a($entity, User::class)) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($entity)));
        }
        /*
         * Encode password when created
         */
        if ($entity->isNew()) {
            $entity->setPassword($this->encoder->encodePassword($entity, $form->get('password')->getData()));
        }
        /*
         * Set default role if none
         */
        if ($entity->getRoles() === []) {
            $entity->setRoles(['ROLE_USER']);
        }

        return true;
    }

    /**
     * getConnectedUser.
     *
     * Note : getUser is final for symfony 4.4
     */
    protected function getConnectedUser(): User
    {
        $user = $this->getUser();
        if (null === $user) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', 'null'));
        } elseif (!is_a($user, User::class)) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        return $user;
    }
}
