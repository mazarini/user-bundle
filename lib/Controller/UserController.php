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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends UserControllerAbstract
{
    /**
     * @var UserPasswordEncoderInterface
     */
    protected $encoder;

    /**
     * @Route("", name="user_index", methods={"GET"})
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
        return parent::new($request, $encoder);
    }

    /**
     * @Route("/show-{id<[1-9]\d*>}.html", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->showAction($user);
    }

    /**
     * @Route("/password-{id<[1-9]\d*>}.html", name="user_change_password", methods={"GET","POST"})
     */
    public function changePassword(Request $request, UserPasswordEncoderInterface $encoder, User $user): Response
    {
        return parent::changePasswordAction($request, $encoder, $user);
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

    protected function getTwigFolder(): string
    {
        return '@MazariniUser/user/';
    }

    /**
     * getCrudAction.
     *
     * @return array<string,string>
     */
    protected function getCrudAction(): array
    {
        return ['_edit' => 'Modifier', '_show' => 'Afficher', '_delete' => 'Supprimer', '_change_password' => 'Mot de passe'];
    }
}
