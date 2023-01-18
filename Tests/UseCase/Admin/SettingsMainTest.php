<?php

/*
 * Copyright (c) 2023.  Baks.dev <admin@baks.dev>
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

namespace BaksDev\Settings\Main\Tests\UseCase\Admin;

use App\Module\Dictionary\Color\Type\Color;
use App\Module\Dictionary\Color\Type\ColorEnum;
use BaksDev\Settings\Main\Entity\Event\SettingsMainEvent;
use BaksDev\Settings\Main\UseCase\Admin\NewEdit;
use BaksDev\Core\Type\Locale\Locale;
use BaksDev\Core\Type\Locale\LocaleEnum;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class SettingsMainTest extends KernelTestCase
{
	public function testSettingsMainDTO() : void
	{
		/** DATA */
		//$SettingsMainEventUid = new SettingsMainEventUid();
		$color = new Color(ColorEnum::AQUAMARINE);
		
		$newDTO = new NewEdit\SettingsMainDTO();
		$newDTO->setColor($color);
		
		/* Проверка заполнения */
		//self::assertEquals($SettingsMainEventUid, $newDTO->getEvent());
		self::assertEquals($color, $newDTO->getColor());
		
		
		/* Проверка мапинга на сущность и обратно */
		$entity = new SettingsMainEvent();
		$entity->setEntity($newDTO);
		
		$editDTO = new NewEdit\SettingsMainDTO();
		$entity->getDto($editDTO);
		
		
		self::assertEquals($editDTO->getEvent(), $entity->getId());
		self::assertNotEquals($editDTO->getEvent(), $newDTO->getEvent());
		self::assertEquals($editDTO->getColor(), $newDTO->getColor());

	}
	
	
	public function testSettingsMainSeoDTO() : void
	{
		/** DATA */
		$local = new Locale(LocaleEnum::RUS);
		$description = 'description';
		$title = 'title';
		$keywords = 'keywords';
		
		$newSeoDTO = new NewEdit\Seo\SettingsMainSeoDTO();
		$newSeoDTO->setLocal($local);
		$newSeoDTO->setDescription($description);
		$newSeoDTO->setTitle($title);
		$newSeoDTO->setKeywords($keywords);
		
		$newDTO = new NewEdit\SettingsMainDTO();
		$newDTO->addSeo($newSeoDTO);
		
		self::assertInstanceOf(ArrayCollection::class, $newDTO->getSeo());
		self::assertCount(1, $newDTO->getSeo());
		
		/* Проверка заполнения */
		/** @var $getSeoDTO NewEdit\Seo\SettingsMainSeoDTO */
		$getSeoDTO = $newDTO->getSeo()->get(0);
		self::assertEquals($local, $getSeoDTO->getLocal());
		self::assertEquals($description, $getSeoDTO->getDescription());
		self::assertEquals($title, $getSeoDTO->getTitle());
		self::assertEquals($keywords, $getSeoDTO->getKeywords());
		

		/* Проверка мапинга на сущность и обратно */
		$entity = new SettingsMainEvent();
		$entity->setEntity($newDTO);
		
		$SettingsMainDTO = new NewEdit\SettingsMainDTO();
		$entity->getDto($SettingsMainDTO);
		
		/** @var $editDTO NewEdit\Seo\SettingsMainSeoDTO */
		$editDTO = $SettingsMainDTO->getSeo()->get(0);
		
		self::assertEquals($local, $editDTO->getLocal());
		self::assertEquals($description, $editDTO->getDescription());
		self::assertEquals($title, $editDTO->getTitle());
		self::assertEquals($keywords, $editDTO->getKeywords());
	}
	
	public function testSettingsMainPhoneDTO() : void
	{
		/** DATA */
		$phone = '+7(123)456-78-90';
		$title = 'мтс';
		$icon = 'mts.png';
		
		$newPhoneDTO = new NewEdit\Phone\SettingsMainPhoneDTO();
		$newPhoneDTO->setPhone($phone);
		$newPhoneDTO->setTitle($title);
		$newPhoneDTO->setIcon($icon);
		
		
		$newDTO = new NewEdit\SettingsMainDTO();
		$newDTO->addPhone($newPhoneDTO);
		
		self::assertInstanceOf(ArrayCollection::class, $newDTO->getPhone());
		self::assertCount(1, $newDTO->getPhone());
		
		/* Проверка заполнения */
		/** @var $getPhoneDTO NewEdit\Phone\SettingsMainPhoneDTO */
		$getPhoneDTO = $newDTO->getPhone()->get(0);
		self::assertEquals($phone, $getPhoneDTO->getPhone());
		self::assertEquals($title, $getPhoneDTO->getTitle());
		self::assertEquals($icon, $getPhoneDTO->getIcon());
		
		
		/* Проверка мапинга на сущность и обратно */
		$entity = new SettingsMainEvent();
		$entity->setEntity($newDTO);
		
		$SettingsMainDTO = new NewEdit\SettingsMainDTO();
		$entity->getDto($SettingsMainDTO);
		
		/** @var $editDTO NewEdit\Phone\SettingsMainPhoneDTO */
		$editDTO = $SettingsMainDTO->getPhone()->get(0);
		
		self::assertEquals($phone, $editDTO->getPhone());
		self::assertEquals($title, $editDTO->getTitle());
		self::assertEquals($icon, $editDTO->getIcon());
	
	}
	
	
	public function testSettingsMainSocialDTO() : void
	{
		/* DATA */
		$icon = 'fb.png';
		$title = 'fb';
		$href = 'https://www.facebook.com';
		
		$newSocialDTO = new NewEdit\Social\SettingsMainSocialDTO();
		
		$newSocialDTO->setIcon($icon);
		$newSocialDTO->setTitle($title);
		$newSocialDTO->setHref($href);
		
		$newDTO = new NewEdit\SettingsMainDTO();
		$newDTO->addSocial($newSocialDTO);
		
		self::assertInstanceOf(ArrayCollection::class, $newDTO->getSocial());
		self::assertCount(1, $newDTO->getSocial());
		
		/** @var $getSocialDTO  NewEdit\Social\SettingsMainSocialDTO */
		$getSocialDTO = $newDTO->getSocial()->get(0);
		self::assertEquals($title, $getSocialDTO->getTitle());
		self::assertEquals($icon, $getSocialDTO->getIcon());
		self::assertEquals($href, $getSocialDTO->getHref());
		
		
		/* Проверка мапинга на сущность и обратно */
		$entity = new SettingsMainEvent();
		$entity->setEntity($newDTO);
		
		$SettingsMainDTO = new NewEdit\SettingsMainDTO();
		$entity->getDto($SettingsMainDTO);
	
		/** @var $editDTO NewEdit\Social\SettingsMainSocialDTO */
		$editDTO = $SettingsMainDTO->getSocial()->get(0);
		
		self::assertEquals($title, $editDTO->getTitle());
		self::assertEquals($icon, $editDTO->getIcon());
		self::assertEquals($href, $editDTO->getHref());
	}
}