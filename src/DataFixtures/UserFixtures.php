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

namespace App\DataFixtures;

use Mazarini\UserBundle\DataFixtures\UserFixturesAbstract;

class UserFixtures extends UserFixturesAbstract
{
    /**
     * getData.
     *
     * @return array<int,mixed>
     */
    protected function getData(): array
    {
        return [
//          [$name, $roles]
            ['admin', ['ROLE_ADMIN']],
            ['user', ['ROLE_USER']],
            ['doe', ['ROLE_USER']],
        ];
    }
}
