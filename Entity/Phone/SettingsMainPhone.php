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

namespace BaksDev\Settings\Main\Entity\Phone;

use BaksDev\Core\Entity\EntityEvent;
use BaksDev\Settings\Main\Entity\Event\SettingsMainEvent;
use BaksDev\Settings\Main\Type\Phone\SettingsMainPhoneUid;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

/* SettingsMainPhone */


#[ORM\Entity]
#[ORM\Table(name: 'settings_main_phone')]
class SettingsMainPhone extends EntityEvent
{
    /** ID */
    #[ORM\Id]
    #[ORM\Column(type: SettingsMainPhoneUid::TYPE)]
    private SettingsMainPhoneUid $id;

    /** Связь на событие  */
    #[ORM\ManyToOne(targetEntity: SettingsMainEvent::class, inversedBy: "phone")]
    #[ORM\JoinColumn(name: 'event', referencedColumnName: 'id')]
    private SettingsMainEvent $event;

    /** Иконка оператора */
    #[ORM\Column(name: 'icon', type: Types::STRING, nullable: true)]
    private ?string $icon;

    /** Краткое описание */
    #[ORM\Column(name: 'title', type: Types::STRING, nullable: true)]
    private ?string $title;

    /** Номер телефона */
    #[ORM\Column(name: 'phone', type: Types::STRING, nullable: false)]
    private string $phone;


    public function __construct(SettingsMainEvent $event)
    {
        $this->id = new SettingsMainPhoneUid();
        $this->event = $event;
    }


    public function __clone()
    {
        $this->id = clone $this->id;
    }

    public function __toString(): string
    {
        return (string) $this->id;
    }

    public function getId(): SettingsMainPhoneUid
    {
        return $this->id;
    }


    public function getDto($dto): mixed
    {
        $dto = is_string($dto) && class_exists($dto) ? new $dto() : $dto;

        if($dto instanceof SettingsMainPhoneInterface || $dto instanceof self)
        {
            return parent::getDto($dto);
        }

        throw new InvalidArgumentException(sprintf('Class %s interface error', $dto::class));
    }

    public function setEntity($dto): mixed
    {
        if($dto instanceof SettingsMainPhoneInterface || $dto instanceof self)
        {
            return parent::setEntity($dto);
        }

        throw new InvalidArgumentException(sprintf('Class %s interface error', $dto::class));
    }

}