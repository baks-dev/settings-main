<?php
/*
 *  Copyright 2025.  Baks.dev <admin@baks.dev>
 *  
 *  Permission is hereby granted, free of charge, to any person obtaining a copy
 *  of this software and associated documentation files (the "Software"), to deal
 *  in the Software without restriction, including without limitation the rights
 *  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *  copies of the Software, and to permit persons to whom the Software is furnished
 *  to do so, subject to the following conditions:
 *  
 *  The above copyright notice and this permission notice shall be included in all
 *  copies or substantial portions of the Software.
 *  
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *  FITNESS FOR A PARTICULAR PURPOSE AND NON INFRINGEMENT. IN NO EVENT SHALL THE
 *  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 *  THE SOFTWARE.
 */

namespace BaksDev\Settings\Main\Entity\Social;

use BaksDev\Core\Entity\EntityEvent;
use BaksDev\Settings\Main\Entity\Event\SettingsMainEvent;
use BaksDev\Settings\Main\Type\Event\SettingsMainEventUid;
use BaksDev\Settings\Main\Type\Social\SettingsMainSocialUid;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'settings_main_social')]
class SettingsMainSocial extends EntityEvent
{
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