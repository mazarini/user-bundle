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

namespace Mazarini\UserBundle\Test;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

trait LoggedTrait
{
    protected function getLoggedClient(string $user): KernelBrowser
    {
        return $this->loggedClient(static::createClient(), $user);
    }

    protected function LoggedClient(KernelBrowser $browser, string $user): KernelBrowser
    {
        if ('admin' === $user) {
            $role = ['ROLE_ADMIN'];
        } elseif ('user' === $user) {
            $role = ['ROLE_USER'];
        } else {
            $role = [];
        }
        if ('anonymous' !== $user) {
            $container = $browser->getContainer();
            if (null === $container) {
                throw new \LogicException('Object container not found');
            }
            $session = $container->get('session');
            if (null === $session) {
                throw new \LogicException('Object session not found');
            }
            if ($session instanceof Session) {
                $session->set('_security_main', serialize(new UsernamePasswordToken($user, null, 'main', $role)));
                $session->save();
                $browser->getCookieJar()->set(new Cookie($session->getName(), $session->getId()));
            } else {
                throw new \LogicException('$session isn\'t Session');
            }
            dump($session);
        }

        return $browser;
    }
}
