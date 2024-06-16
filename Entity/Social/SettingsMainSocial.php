<?php
/*
 *  Copyright 2022.  Baks.dev <admin@baks.dev>
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *  http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *   limitations under the License.
 *
 */

namespace BaksDev\Settings\Main\Entity\Social;

use BaksDev\Settings\Main\Entity\Event\SettingsMainEvent;
use BaksDev\Settings\Main\Type\Event\SettingsMainEventUid;
use BaksDev\Settings\Main\Type\Social\SettingsMainSocialUid;
use BaksDev\Core\Entity\EntityEvent;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'settings_main_social')]
class SettingsMainSocial extends EntityEvent
{
    public const TABLE = "settings_main_social";

    /** ID */
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: SettingsMainSocialUid::TYPE)]
    private SettingsMainSocialUid $id;

    /** Связь на событие  */
    #[ORM\ManyToOne(targetEntity: SettingsMainEvent::class, inversedBy: "social")]
    #[ORM\JoinColumn(name: 'event', referencedColumnName: 'id')]
    protected SettingsMainEvent $event;

    /** Ссылка */
    #[ORM\Column(name: 'href', type: Types::STRING, nullable: false)]
    private string $href;

    /** Иконка соцсети */
    #[ORM\Column(name: 'icon', type: Types::STRING, nullable: true)]
    private ?string $icon;

    /** Краткое описание */
    #[ORM\Column(name: 'title', type: Types::STRING, nullable: false)]
    private string $title;


    public function __construct(SettingsMainEvent $event)
    {
        $this->id = new SettingsMainSocialUid();
        $this->event = $event;
    }


    public function __clone(): void
    {
        $this->id = clone $this->id;
    }

    public function __toString(): string
    {
        return (string) $this->id;
    }

    public function setSettings(SettingsMainEvent|SettingsMainEventUid $event): void
    {
        $this->event = $event instanceof SettingsMainEvent ? $event->getId() : $event;
    }

    public function addSocial(string $href, string $title, ?string $icon = null): void
    {
        $this->href = $href;
        $this->title = $title;
        $this->icon = $icon;
    }

}