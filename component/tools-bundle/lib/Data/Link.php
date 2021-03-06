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

namespace Mazarini\ToolsBundle\Data;

class Link implements LinkInterface
{
    use LinkTrait;

    public function __construct(string $name, string $url, string $label = '')
    {
        $this->name = $name;
        $this->url = $url;
        if ('' === $label) {
            $this->label = ucfirst($name);
        } else {
            $this->label = $label;
        }
    }

    public function getClass(): string
    {
        if ('' === $this->url) {
            return ' active';
        }
        if ('#' === $this->url) {
            return ' disabled';
        }

        return '';
    }
}
