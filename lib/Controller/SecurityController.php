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

use Mazarini\ToolsBundle\Controller\AbstractController;
use Mazarini\ToolsBundle\Data\Data;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="security_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastLogin = $authenticationUtils->getLastUsername();

        return $this->dataRender('login.html.twig', ['last_login' => $lastLogin, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout(): void
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }

    protected function setUrl(Data $data): void
    {
    }

    protected function getTwigFolder(): string
    {
        return '@MazariniUser/security/';
    }
}
