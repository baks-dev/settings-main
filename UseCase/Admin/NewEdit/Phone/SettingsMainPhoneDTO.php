<?php
/*
 * Copyright (c) 2022.  Baks.dev <admin@baks.dev>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *  http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace BaksDev\Settings\Main\UseCase\Admin\NewEdit\Phone;

use BaksDev\Settings\Main\Entity\Phone\SettingsMainPhoneInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class SettingsMainPhoneDTO implements SettingsMainPhoneInterface
{

    public ?string $icon;

    public ?string $title;

    #[Assert\NotBlank]
    #[Assert\Phone]
    public ?string $phone;


    /**
     * @return string|null
     */
    public function getIcon(): ?string
    {
        return $this->icon;
    }


    /**
     * @param string|null $icon
     */
    public function setIcon(?string $icon): void
    {
        $this->icon = $icon;
    }


    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }


    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }


    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }


    /**
     * @param string|null $phone
     */
    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }


    //    public function __construct(string $icon = null, string $title = null, string $phone = null)
    //    {
    //        $this->icon = $icon;
    //        $this->title = $title;
    //        $this->phone = $phone;
    //    }

}

