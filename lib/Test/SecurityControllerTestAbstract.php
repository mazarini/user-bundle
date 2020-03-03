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

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class SecurityControllerTestAbstract extends WebTestCase
{
    use LoggedTrait;

    /**
     * @dataProvider getUrls
     */
    public function testAnonymous(string $url, string $userAuth): void
    {
        $this->assertSecurity($url, $userAuth, 'anonymous');
    }

    /**
     * @dataProvider getUrls
     */
    public function testUser(string $url, string $userAuth): void
    {
        $this->assertSecurity($url, $userAuth, 'user', 'ROLE_USER');
    }

    /**
     * @dataProvider getUrls
     */
    public function testAdmin(string $url, string $userAuth): void
    {
        $this->assertSecurity($url, $userAuth, 'admin', 'ROLE_ADMIN');
    }

    protected function assertSecurity(string $url, string $userAuth, string $user, string $role = ''): void
    {
        $client = $this->getLoggedClient($user);
        foreach (['anonymous', 'user', 'admin'] as $user) {
            if ($userAuth === $user || 'anonymous' === $userAuth || 'admin' === $user) {
                $statusCode = Response::HTTP_OK;
            } else {
                $statusCode = Response::HTTP_FOUND;
            }
            $client->request('GET', $url);
            $response = $client->getResponse();
            $responseCode = $response->getStatusCode();
            $this->assertSame($statusCode,$responseCode,
                sprintf('For %s, the %s URL loads correctly with status %d (really %d)', $user, $url, $statusCode, $responseCode));
            if ($response instanceof RedirectResponse) {
                $responseUrl = $response->getTargetUrl();
                $this->assertSame('/login.html',$responseUrl,
                    sprintf('The %s public URL redirect correctly to /login.html (really %s)', $url, $responseUrl));
            }
        }
    }

    /**
     * getUrls.
     *
     * @return \Traversable<int,array>
     */
    abstract public function getUrls(): \Traversable;
}
