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

namespace Mazarini\UserBundle\DataFixtures;

use App\Entity\User;
use Mazarini\ToolsBundle\Entity\EntityInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

abstract class UserFixturesAbstract extends EntityFixturesAbstract
{
    /**
     * @var UserPasswordEncoderInterface
     */
    protected $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * getFromData.
     *
     * @param array<int,mixed> $arrayData
     */
    protected function getFromData(array $arrayData): EntityInterface
    {
        [$username, $roles] = $arrayData;

        return $this->getEntity($username, $roles);
    }

    protected function getFromNumber(int $number): EntityInterface
    {
        return $this->getEntity(sprintf('zz-%02d', $number));
    }

    /**
     * getEntity.
     *
     * @param array<int,string> $roles
     */
    protected function getEntity(string $username, array $roles = ['ROLE_USER']): EntityInterface
    {
        $user = new User();
        $user->setUsername($username);
        $user->setPassword($this->passwordEncoder->encodePassword($user, $username));
        $user->setEmail($username.'@example.com');
        $user->setRoles($roles);
        dump($user);

        return $user;
    }

    /**
     * getData.
     *
     * @return array<int,mixed>
     */
    abstract protected function getData(): array;
}
