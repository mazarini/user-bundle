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

namespace App\Tests\Controller;

use Mazarini\UserBundle\Test\LoggedTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends WebTestCase
{
    use LoggedTrait;

    /**
     * @dataProvider getUrls
     */
    public function testUrls(string $user, string $method, string $url, string $targetUrl): void
    {
        $statusCode = Response::HTTP_FOUND;
        $parameters = [];
        $client = $this->getLoggedClient($user);
        $client->request($method, $url, $parameters);
        $response = $client->getResponse();
        $this->assertRedirect($url, $statusCode, $targetUrl, $response);
    }

    protected function assertRedirect(string $url, int $statusCode, string $targetUrl, Response $response): void
    {
        $this->assertTrue($response instanceof RedirectResponse,
             sprintf('The url "%s" is redirect (actual statuscode %d / "%s")', $url, $response->getStatusCode(), Response::$statusTexts[$response->getStatusCode()])
        );
        if ($response instanceof RedirectResponse) {
            $this->assertSame(
            $statusCode,
            $response->getStatusCode(),
                sprintf('The url "%s" is redirect with status %d / "%s" (actual statuscode %d / "%s")', $url, $statusCode, Response::$statusTexts[$statusCode], $response->getStatusCode(), Response::$statusTexts[$response->getStatusCode()])
            );
            $realTargetUrl = str_replace('http://localhost', '', $response->getTargetUrl());
            $this->assertSame(
            $targetUrl,
            $realTargetUrl,
                sprintf('The url "%s" is redirect to "%s" (actual target "%s")', $url, $targetUrl, $realTargetUrl)
            );
        }
    }

    /**
     * getUrls.
     *
     * @return \Traversable<int,array>
     */
    public function getUrls(): \Traversable
    {
        yield['anonymous', 'GET', '/profile/show.html', '/login.html'];
        yield['anonymous', 'GET', '/profile/edit.html', '/login.html'];
        yield['anonymous', 'GET', '/profile/password.html', '/login.html'];
        yield['anonymous', 'GET', '/admin/user/page-1.html', '/login.html'];
        yield['anonymous', 'GET', '/admin/user/new.html', '/login.html'];
        yield['anonymous', 'GET', '/admin/user/show-1.html', '/login.html'];
        yield['anonymous', 'GET', '/admin/user/password-1.html', '/login.html'];
        yield['anonymous', 'GET', '/admin/user/edit-1.html', '/login.html'];
        yield['anonymous', 'DELETE', '/admin/user/delete-1.html', '/login.html'];
    }
}
