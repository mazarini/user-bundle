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

namespace App\Tests\Repository;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityNotFoundException;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var UserRepository
     */
    private $repository;

    protected function setUp(): void
    {
        if (null === $this->entityManager) {
            $kernel = self::bootKernel();
            $doctrine = $kernel->getContainer()->get('doctrine');
            if ((null !== $doctrine) && (method_exists($doctrine, 'getManager'))) {
                $this->entityManager = $doctrine->getManager();
            } else {
                throw new RuntimeException('Doctrine not available in container.');
            }
            $this->repository = $this->entityManager->getRepository(user::class);
        }
    }

    /**
     * testCreateEntity.
     */
    public function testCreateEntity(): void
    {
        // Create
        $id = $this->initEntity();
        // Control value
        $this->assertEntity($id);
    }

    /**
     * testUpdateEntity.
     */
    public function testUpdateEntity(): void
    {
        // Create
        $id = $this->initEntity();
        // Update
        $this->initEntity($id, '_modified');
        // Control value after update
        $this->assertEntity($id, '_modified');
    }

    /**
     * testCreateEntity.
     */
    public function testDeleteEntity(): void
    {
        // Create
        $id = $this->initEntity();
        // Find Entity and delete
        $entity = $this->FindUser($id);
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
        // Control
        $entity = $this->entityManager->getRepository(user::class)->find($id);
        $this->assertNull($entity);
    }

    public function testPageEntity(): void
    {
        $count = 3;
        $page = $this->repository->getPage(1, $count)->getEntities();
        $this->assertCount($count, $page);
        $entity = $page->current();
        $this->assertSame(2, $entity->getId());
        $page->seek($count - 1);
        $entity = $page->current();
        $this->assertSame(1, $entity->getId());
    }

    protected function initEntity(?int $id = null, string $salt = ''): int
    {
        if (null === $id) {
            $entity = new User();
        } else {
            $entity = $this->findUser($id);
        }
        $entity->setUsername('name'.$salt);
        $entity->setPassword('pass'.$salt);
        $entity->setEmail('me@example.com'.$salt);
        $entity->setRoles(['ROLE_ADMIN'.$salt]);
        if ($entity->isNew()) {
            $this->entityManager->persist($entity);
        }
        $this->entityManager->flush();

        return $entity->getId();
    }

    protected function assertEntity(int $id, string $salt = ''): void
    {
        $entity = $this->findUser($id);
        $this->assertSame('name'.$salt, $entity->getUsername());
        $this->assertSame('pass'.$salt, $entity->getPassword());
        $this->assertSame('me@example.com'.$salt, $entity->getEmail());
        $this->assertSame(['ROLE_ADMIN'.$salt], $entity->getRoles());
    }

    protected function findUser(int $id): User
    {
        $entity = $this->entityManager->getRepository(user::class)->find($id);
        if ((null !== $entity) && is_a($entity, User::class)) {
            return $entity;
        }
        throw EntityNotFoundException::fromClassNameAndIdentifier(User::class, ['id' => (string) $id]);
    }
}
