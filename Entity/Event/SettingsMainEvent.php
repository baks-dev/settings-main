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

namespace BaksDev\Settings\Main\Entity\Event;

use BaksDev\Core\Entity\EntityEvent;
use BaksDev\Core\Type\Modify\Modify\ModifyActionNew;
use BaksDev\Core\Type\Modify\Modify\ModifyActionUpdate;
use BaksDev\Core\Type\Modify\ModifyAction;
use BaksDev\Reference\Color\Type\Color;
use BaksDev\Settings\Main\Entity\Modify\SettingsMainModify;
use BaksDev\Settings\Main\Entity\Phone\SettingsMainPhone;
use BaksDev\Settings\Main\Entity\Seo\SettingsMainSeo;
use BaksDev\Settings\Main\Entity\SettingsMain;
use BaksDev\Settings\Main\Entity\Social\SettingsMainSocial;
use BaksDev\Settings\Main\Type\Event\SettingsMainEventUid;
use BaksDev\Settings\Main\Type\Id\SettingsMainIdentificator;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

/* SettingsMainEvent */


#[ORM\Entity]
#[ORM\Table(name: 'settings_main_event')]
#[ORM\Index(columns: ['setting'])]
class SettingsMainEvent extends EntityEvent
{
    /** ID события */
    #[ORM\Id]
    #[ORM\Column(type: SettingsMainEventUid::TYPE)]
    protected SettingsMainEventUid $id;

    /** ID SettingsMain */
    #[ORM\Column(type: SettingsMainIdentificator::TYPE)]
    protected ?SettingsMainIdentificator $setting = null;

    /** Модификатор */
    #[ORM\OneToOne(targetEntity: SettingsMainModify::class, mappedBy: 'event', cascade: ['all'], fetch: 'EAGER')]
    protected SettingsMainModify $modify;

    /** Цвет */
    #[ORM\Column(name: 'color', type: Color::TYPE, nullable: false)]
    protected ?Color $color;

    /** Контактные телефоны */
    #[ORM\OneToMany(targetEntity: SettingsMainPhone::class, mappedBy: 'event', cascade: ['all'], fetch: 'EAGER')]
    protected Collection $phone;

    /** Настройки SEO по умолчанию */
    #[ORM\OneToMany(targetEntity: SettingsMainSeo::class, mappedBy: 'event', cascade: ['all'], fetch: 'EAGER')]
    protected Collection $seo;

    /** Социальные сети */
    #[ORM\OneToMany(targetEntity: SettingsMainSocial::class, mappedBy: 'event', cascade: ['all'], fetch: 'EAGER')]
    protected Collection $social;


    public function __construct()
    {
        $this->id = new SettingsMainEventUid();
        $this->modify = new SettingsMainModify($this, new ModifyAction(ModifyActionNew::class));
    }


    public function __clone()
    {
        $this->id = clone $this->id;
    }

    public function __toString(): string
    {
        return (string) $this->id;
    }

    public function getId(): SettingsMainEventUid
    {
        return $this->id;
    }

    public function getSetting(): ?SettingsMainIdentificator
    {
        return $this->setting;
    }


    public function setSetting(SettingsMain|SettingsMainIdentificator $setting): void
    {
        $this->setting = $setting instanceof SettingsMain ? $setting->getId() : $setting;
    }


    public function getDto($dto): mixed
    {
        $dto = is_string($dto) && class_exists($dto) ? new $dto() : $dto;

        if($dto instanceof SettingsMainEventInterface || $dto instanceof self)
        {
            return parent::getDto($dto);
        }

        throw new InvalidArgumentException(sprintf('Class %s interface error', $dto::class));
    }

    public function setEntity($dto): mixed
    {
        if($dto instanceof SettingsMainEventInterface || $dto instanceof self)
        {
            return parent::setEntity($dto);
        }

        throw new InvalidArgumentException(sprintf('Class %s interface error', $dto::class));
    }

}