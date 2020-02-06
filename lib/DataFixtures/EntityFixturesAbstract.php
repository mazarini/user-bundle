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

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Mazarini\ToolsBundle\Entity\EntityInterface;

abstract class EntityFixturesAbstract extends Fixture
{
    /**
     * @var int
     */
    protected $number = 11;

    public function load(ObjectManager $manager): void
    {
        foreach ($this->getData() as $arrayData) {
            $manager->persist($this->getFromData($arrayData));
        }

        $manager->flush();

        for ($i = 1; $i <= $this->number; ++$i) {
            $manager->persist($this->getFromNumber($i));
        }

        $manager->flush();
    }

    /**
     * getFromData.
     *
     * @param array<int,mixed> $arrayData
     */
    abstract protected function getFromData(array $arrayData): EntityInterface;

    /**
     * getFromNumber.
     */
    abstract protected function getFromNumber(int $number): EntityInterface;

    /**
     * getData.
     *
     * @return array<int,mixed>
     */
    abstract protected function getData(): array;
}
