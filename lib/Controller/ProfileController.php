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
use Mazarini\ToolsBundle\Data\Data;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfileController extends UserControllerAbstract
{
    /**
     * @var UserPasswordEncoderInterface
     */
    protected $encoder;

    /**
     * @Route("", name="profile_index", methods={"GET","POST"})
     */
    public function index(): Response
    {
        return  $this->redirectToRoute('profile_show', [], Response::HTTP_MOVED_PERMANENTLY);
    }

    /**
     * @Route("/new.html", name="profile_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        return $this->editAction($request, new User(), UserType::class);
    }

    /**
     * @Route("/show.html", name="profile_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function show(): Response
    {
        return $this->showAction($this->getConnectedUser());
    }

    /**
     * @Route("/edit.html", name="profile_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function edit(Request $request): Response
    {
        return $this->editAction($request, $this->getConnectedUser(), UserType::class);
    }

    /**
     * @Route("/password.html", name="profile_change_password", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function changePassword(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        return parent::changePasswordAction($request, $encoder, $this->getConnectedUser());
    }

    protected function setUrl(Data $data): void
    {
        $data->addLink('edit', $data->generateUrl('_edit'));
        $data->addLink('show', $data->generateUrl('_show'), 'View');
        $data->addLink('change_password', $data->generateUrl('_change_password'), 'Mot de passe');
        $data->addLink('index', '/', 'Liste');
    }

    protected function getTwigFolder(): string
    {
        return '@MazariniUser/profile/';
    }
}
