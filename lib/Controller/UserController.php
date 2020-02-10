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
use App\Repository\UserRepository;
use Mazarini\CrudBundle\Controller\AbstractCrudController;
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
 * @Route("/user")
 * @IsGranted("ROLE_ADMIN")
 */
class UserController extends AbstractCrudController
{
    /**
     * @var UserPasswordEncoderInterface
     */
    protected $encoder;

    /**
     * @var FormInterface<mixed>
     */
    protected $form;

    public function __construct(RequestStack $requestStack, UrlGeneratorInterface $router, string $baseRoute = 'user')
    {
        parent::__construct($requestStack, $router, $baseRoute);
        $this->twigFolder = '@MazariniUser/user/';
    }

    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->indexAction();
    }

    /**
     * @Route("/page-{page<[1-9]\d*>}.html", name="user_page", methods={"GET"})
     */
    public function page(UserRepository $userRepository, int $page = 1): Response
    {
        return $this->PageAction($userRepository, $page);
    }

    /**
     * @Route("/new.html", name="user_new", methods={"GET","POST"})
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
     * @Route("/show-{id<[1-9]\d*>}.html", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->showAction($user);
    }

    /**
     * @Route("/edit-{id<[1-9]\d*>}.html", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        return $this->editAction($request, $user, UserType::class);
    }

    /**
     * delete.
     *
     * @Route("/delete-{id<[1-9]\d*>}.html", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        return $this->deleteAction($request, $user);
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
}
