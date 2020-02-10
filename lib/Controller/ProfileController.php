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
use App\Form\UserType;
use Mazarini\CrudBundle\Controller\AbstractCrudController;
use Mazarini\ToolsBundle\Controller\AbstractController;
use Mazarini\ToolsBundle\Data\Data;
use Mazarini\ToolsBundle\Entity\EntityInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

/**
 * @Route("/profile")
 */
class ProfileController extends AbstractCrudController
{
    /**
     * @var UserPasswordEncoderInterface
     */
    protected $encoder;

    /**
     * @var FormInterface<mixed>
     */
    protected $form;

    public function __construct(RequestStack $requestStack, UrlGeneratorInterface $router, string $baseRoute = 'profile')
    {
        parent::__construct($requestStack, $router, $baseRoute);
        $this->twigFolder = '@MazariniUser/profile/';
    }

    /**
     * @Route("/new.html", name="profile_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $this->encoder = $encoder;
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        $this->form = $form;

        return $this->editAction($request, $user, UserType::class);
    }

    /**
     * @Route("/show.html", name="profile_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function show(): Response
    {
        $user = $this->getUser();
        if (null === $user) {
            return $this->redirectToRoute('security_login');
        } elseif (!is_a($user, EntityInterface::class)) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        return $this->showAction($user);
    }

    /**
     * @Route("/edit.html", name="profile_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function edit(Request $request): Response
    {
        $user = $this->getUser();
        if (null === $user) {
            return $this->redirectToRoute('security_login');
        } elseif (!is_a($user, EntityInterface::class)) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        return $this->editAction($request, $user, UserType::class);
    }

    protected function valid(EntityInterface $entity): bool
    {
        if (!is_a($entity, User::class)) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($entity)));
        }
        /*
         * Encode password when created
         */
        if ($entity->isNew()) {
            $entity->setPassword($this->encoder->encodePassword($entity, $this->form->get('password')->getData()));
        }
        /*
         * Set default role if none
         */
        if ($entity->getRoles() === []) {
            $entity->setRoles(['ROLE_USER']);
        }

        return true;
    }

    protected function initUrl(Data $data): AbstractController
    {
        $data->addLink('edit', $data->generateUrl('_edit'));
        $data->addLink('show', $data->generateUrl('_show'), 'View');

        return $this;
    }
}
