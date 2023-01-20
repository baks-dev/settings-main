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

use BaksDev\Reference\Color\Type\Color;
use BaksDev\Reference\Color\Type\ColorEnum;
use BaksDev\Settings\Main\Type\Event\SettingsMainEventUid;
use BaksDev\Settings\Main\UseCase\Admin\NewEdit;
use BaksDev\Settings\Main\UseCase\Admin\NewEdit\SettingsMainForm;
use BaksDev\Core\Type\Locale\Locale;
use BaksDev\Core\Type\Locale\LocaleEnum;
use Symfony\Component\Form\Test\TypeTestCase;


final class SettingsMainFormTest extends TypeTestCase
{
	public function testSubmitValidData()
	{
		/* DATA */
		$SettingsMainEventUid = new SettingsMainEventUid();
		$color = new Color(ColorEnum::AQUAMARINE);

		/** SEO */
		$local = new Locale(LocaleEnum::RUS);
		$description = 'description';
		$seotitle = 'title';
		$keywords = 'keywords';
		
		/** PHONE */
		$phone = '+7(123)456-78-90';
		$title = 'Beeline';
		$icon = 'Beeline';
		
		
		/* SOCIAL */
		$socicon = 'fb';
		$soctitle = 'Facebook';
		$href = 'https://www.facebook.com';
		
		$newDTO = new NewEdit\SettingsMainDTO();
		
		
		/* FORM */
		$form = $this->factory->create(SettingsMainForm::class, $newDTO);
		
		$formData = [
			'color' => (string) $color,
			'phone' => [
				[
					'icon' => $icon,
					'phone' => $phone,
					'title' => $title,
				],
			],
			
			'seo' => [
				[
					'title' => $seotitle,
					'description' => $description,
					'keywords' => $keywords,
				],
			],
			
			'social' => [
				[
					'icon' => $socicon,
					'title' => $soctitle,
					'href' => $href,
				],
			],
			'settings_main' => true, // btn
		];
		
		$form->submit($formData);
		self::assertTrue($form->isSynchronized());
		
		/* OBJECT */
		$expected = new NewEdit\SettingsMainDTO();
		$expected->setColor($color);
		
		$newSeoDTO = new NewEdit\Seo\SettingsMainSeoDTO();
		$newSeoDTO->setLocal($local);
		$newSeoDTO->setDescription($description);
		$newSeoDTO->setTitle($seotitle);
		$newSeoDTO->setKeywords($keywords);
		$expected->addSeo($newSeoDTO);
		
		$newPhoneDTO = new NewEdit\Phone\SettingsMainPhoneDTO();
		$newPhoneDTO->setPhone($phone);
		$newPhoneDTO->setTitle($title);
		$newPhoneDTO->setIcon($icon);
		$expected->addPhone($newPhoneDTO);
		
		$newSocialDTO = new NewEdit\Social\SettingsMainSocialDTO();
		
		$newSocialDTO->setIcon($socicon);
		$newSocialDTO->setTitle($soctitle);
		$newSocialDTO->setHref($href);
		$expected->addSocial($newSocialDTO);
		

		self::assertEquals($expected, $newDTO);
		
		
		/* VIEW */
		$view = $form->createView();
		$children = $view->children;
		
		foreach(array_keys($formData) as $key)
		{
			self::assertArrayHasKey($key, $children);
		}
	}
}