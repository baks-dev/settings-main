<?php
/*
 *  Copyright 2022-2025.  Baks.dev <admin@baks.dev>
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

namespace BaksDev\Settings\Main\UseCase\Admin\NewEdit;

use BaksDev\Core\Type\Locale\Locale;
use BaksDev\Reference\Color\Type\Color;
use BaksDev\Reference\Color\Type\ColorEnum;
use BaksDev\Reference\Color\Type\Colors\Collection\Blue;
use BaksDev\Settings\Main\Entity\Event\SettingsMainEventInterface;
use BaksDev\Settings\Main\Type\Event\SettingsMainEventUid;
use BaksDev\Settings\Main\UseCase\Admin\NewEdit\Phone\SettingsMainPhoneDTO;
use BaksDev\Settings\Main\UseCase\Admin\NewEdit\Seo\SettingsMainSeoDTO;
use BaksDev\Settings\Main\UseCase\Admin\NewEdit\Social\SettingsMainSocialDTO;
use Doctrine\Common\Collections\ArrayCollection;

//use BaksDev\Core\Type\Reference\Color\Color;
//use BaksDev\Core\Type\Reference\Color\ColorEnum;

final class SettingsMainDTO implements SettingsMainEventInterface
{
    private ?SettingsMainEventUid $id = null;

    /** Контактный телефон */
    private ?ArrayCollection $phone;

    /** SEO */
    private ?ArrayCollection $seo;

    /** Ссылки на страницы в соцсетях*/
    private ?ArrayCollection $social;

    /** Основной цвет системы */
    private ?Color $color;

    /** Логотип */
    private ?string $logo;


    public function __construct(?Color $color = new Color(new Blue()))
    {
        $this->phone = new ArrayCollection();
        $this->seo = new ArrayCollection();
        $this->social = new ArrayCollection();

        $this->color = $color;
    }


    public function getEvent(): ?SettingsMainEventUid
    {
        return $this->id;
    }

    //    public function setId(SettingsMainEventUid $id) : void
    //    {
    //        $this->id = $id;
    //    }

    /** Логотип */
    public function getLogo(): ?string
    {
        return $this->logo;
    }


    public function setLogo(?string $logo): void
    {
        $this->logo = $logo;
    }


    /** Основной цвет системы */

    public function getColor(): ?Color
    {
        return $this->color;
    }


    public function setColor(Color|string|null $color): void
    {
        if($color instanceof Color)
        {
            $this->color = $color;

            return;
        }
        $this->color = new Color($color);
    }


    /** Контактный телефон */

    public function getPhone(): ?ArrayCollection
    {
        return $this->phone;
    }


    public function addPhone(SettingsMainPhoneDTO $phone): void
    {
        $this->phone->add($phone);
    }


    public function removePhone(SettingsMainPhoneDTO $phone): void
    {
        $this->phone->removeElement($phone);
    }


    /** SEO */

    public function getSeo(): ?ArrayCollection
    {
        /* Вычисляем расхождение и добавляем неопределенные локали */

        foreach(Locale::diffLocale($this->seo) as $locale)
        {
            $CategorySeoDTO = new SettingsMainSeoDTO();
            $CategorySeoDTO->setLocal($locale);
            $this->addSeo($CategorySeoDTO);
        }

        return $this->seo;
    }


    public function addSeo(SettingsMainSeoDTO $seo): void
    {
        if(empty($seo->getLocal()->getLocalValue()))
        {
            return;
        }

        $this->seo->add($seo);
    }


    public function removeSeo(SettingsMainSeoDTO $seo): void
    {
        $this->seo->removeElement($seo);
    }


    /** Ссылки на страницы в соцсетях */

    public function getSocial(): ?ArrayCollection
    {
        return $this->social;
    }


    public function addSocial(SettingsMainSocialDTO $social): void
    {
        $this->social->add($social);
    }


    public function removeSocial(SettingsMainSocialDTO $social): void
    {
        $this->social->removeElement($social);
    }

}
