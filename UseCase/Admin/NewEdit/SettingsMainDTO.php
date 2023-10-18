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

namespace BaksDev\Settings\Main\UseCase\Admin\NewEdit;

use BaksDev\Core\Type\Locale\Locale;
use BaksDev\Reference\Color\Type\Color;
use BaksDev\Reference\Color\Type\ColorEnum;
use BaksDev\Reference\Color\Type\Colors\Blue;
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
	
	
	public function getEvent() : ?SettingsMainEventUid
	{
		return $this->id;
	}
	
	//    public function setId(SettingsMainEventUid $id) : void
	//    {
	//        $this->id = $id;
	//    }
	
	/** Логотип */
	public function getLogo() : ?string
	{
		return $this->logo;
	}
	
	
	public function setLogo(?string $logo) : void
	{
		$this->logo = $logo;
	}
	
	
	/** Основной цвет системы */
	
	public function getColor() : ?Color
	{
		return $this->color;
	}
	
	
	public function setColor(Color|string|null $color) : void
	{
		if($color instanceof Color)
		{
			$this->color = $color;
			
			return;
		}
		$this->color = new Color($color);
	}
	
	
	/** Контактный телефон */
	
	public function getPhone() : ?ArrayCollection
	{
		return $this->phone;
	}
	
	
	public function addPhone(SettingsMainPhoneDTO $phone) : void
	{
		$this->phone->add($phone);
	}
	
	
	public function removePhone(SettingsMainPhoneDTO $phone) : void
	{
		$this->phone->removeElement($phone);
	}
	
	
	/** SEO */
	
	public function getSeo() : ?ArrayCollection
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
	
	
	public function addSeo(SettingsMainSeoDTO $seo) : void
	{
        if(empty($seo->getLocal()->getLocalValue()))
        {
            return;
        }

		$this->seo->add($seo);
	}
	
	
	public function removeSeo(SettingsMainSeoDTO $seo) : void
	{
		$this->seo->removeElement($seo);
	}
	
	
	/** Ссылки на страницы в соцсетях */
	
	public function getSocial() : ?ArrayCollection
	{
		return $this->social;
	}
	
	
	public function addSocial(SettingsMainSocialDTO $social) : void
	{
		$this->social->add($social);
	}
	
	
	public function removeSocial(SettingsMainSocialDTO $social) : void
	{
		$this->social->removeElement($social);
	}
	
}

