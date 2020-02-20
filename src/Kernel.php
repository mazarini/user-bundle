<?php

/*
 * Copyright (C) 2019-2020 Mazarini <mazarini@protonmail.com>.
 * This file is part of mazarini/tools-bundle.
 *
 * mazarini/tools-bundle is free software: you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or (at your
 * option) any later version.
 *
 * mazarini/tools-bundle is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 * or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for
 * more details.
 *
 * You should have received a copy of the GNU General Public License
 */

namespace App;

use Mazarini\ToolsBundle\Kernel as BaseKernel;
use App\Controller\HomeController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class Kernel extends BaseKernel
{ 
    public function getProjectDir(): string
    {
        return \dirname(__DIR__);
    }

        /**
     * configureRoutes.
     *
     * RouteCollectionBuilder : 4.4 => 5.0
     * RoutingConfigurator : 5.1 => ?
     *
     * @param object $routes
     */
    protected function configureRoutes($routes): void
    {
        parent::configureRoutes($routes);
        if (method_exists($routes,'add')) {
            if ($routes instanceof RoutingConfigurator) {
                $routes->add('homepage', '/')->controller([HomeController::class, 'home']);
            } else {
                $routes->add('/', HomeController::class.'::home', 'homepage')->setMethods('GET');
            }
        }
    }
}
