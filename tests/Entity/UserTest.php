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

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Traversable;

class UserTest extends TestCase
{
    /**
     * testNewEntity.
     */
    public function testNewEntity(): void
    {
        $entity = new User();
        $this->assertSame($entity->getId(), 0);
        $this->assertTrue($entity->isNew());
    }

    /**
     * testOldEntity.
     */
    public function testOldEntity(): void
    {
        $entity = new User();
        $reflectionClass = new ReflectionClass(User::class);
        $reflectionProperty = $reflectionClass->getProperty('id');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($entity, 1);

        $this->assertSame($entity->getId(), 1);
        $this->assertFalse($entity->isNew());
    }

    /**
     * testProperty.
     *
     * @dataProvider getProperties
     *
     * @param number|string|array<mixed>|object|null $default
     * @param number|string|array<mixed>|object      $value
     */
    public function testProperty(string $property, $default, $value): void
    {
        $entity = new User();
        $setter = 'set'.$property;
        $getter = 'get'.$property;
        if (null !== $default) {
            $this->assertSame($default, $entity->$getter());
        }
        $entity->$setter($value);
        $this->assertSame($value, $entity->$getter());
    }

    /**
     * getProperties.
     *
     * @return Traversable<array>
     */
    public function getProperties(): Traversable
    {
        /*
         * [$property,$default,$value]
         */
        yield ['Username', '', 'name'];
        yield ['Password', '', 'pass'];
        yield ['Email', '', 'me@example.com'];
        yield ['Roles', [], ['ROLE_ADMIN']];
        yield ['Roles', [], ['ROLE_USER']];
    }
}
