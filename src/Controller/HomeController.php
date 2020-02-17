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

namespace App\Controller;

use Exception;
use Mazarini\ToolsBundle\Controller\AbstractController;
use Mazarini\ToolsBundle\Data\Data;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Exception\NoConfigurationException;

/**
 * @Route("/")
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/_homepage", name="home_page", methods={"GET","POST"})
     * @Route("/_homepage", name="homepage", methods={"GET","POST"})
     */
    public function home(Request $request): Response
    {
        $parameters = [];
        try {
            $router = $this->get('router');
            if ($router instanceof Router) {
                $route = $router->match('/')['_route'];
            } else {
                throw new Exception('Router not found in container');
            }
        } catch (NoConfigurationException $e) {
            return $this->dataRender('security/homepage.html.twig', ['exception' => false]);
        } catch (Exception $e) {
            return $this->dataRender('security/homepage.html.twig', ['exception' => $e]);
        }

        return $this->redirect('/', Response::HTTP_MOVED_PERMANENTLY);
    }

    protected function setUrl(Data $data): void
    {
    }

    protected function getTwigFolder(): string
    {
        return '';
    }
}
