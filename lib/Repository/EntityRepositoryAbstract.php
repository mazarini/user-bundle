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

namespace Mazarini\UserBundle\Repository;

use App\Entity\User;
// use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Mazarini\PaginationBundle\Repository\AbstractRepository;
use Mazarini\ToolsBundle\Pagination\Pagination;
use Traversable;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntityRepositoryAbstract extends AbstractRepository
{
    /**
     * @var string
     */
    protected $orderColumn = 'e.id';

    /**
     * @var string
     */
    protected $orderDirection = 'ASC';

    public function getPage(int $currentPage = 1, int $pageSize = 10): Pagination
    {
        $totalCount = $this->totalCount();
        if (0 === $totalCount) {
            $current = 1;
            $result = new \ArrayIterator([]);
        } else {
            $current = Pagination::CURRENT_PAGE($currentPage, $pageSize, $totalCount);
            if ($current === $currentPage) {
                $result = $this->getResult(($currentPage - 1) * $pageSize, $pageSize);
            } else {
                $result = new \ArrayIterator([]);
            }
        }

        return new Pagination($result, $currentPage, $totalCount, $pageSize);
    }

    protected function totalCount(): int
    {
        return $this->getPageQueryBuilder()
            ->select('count(e.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * getResult.
     *
     * @return Traversable<mixed, mixed>
     */
    protected function getResult(int $start, int $pageSize): Traversable
    {
        $result = $this->getPageQueryBuilder()
            ->orderBy($this->orderColumn, $this->orderDirection)
            ->setFirstResult($start)
            ->setMaxResults($pageSize)
            ->getQuery()
            ->getResult();

        return new \ArrayIterator($result);
    }

    protected function getPageQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('e');
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
