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

namespace BaksDev\Settings\Main\UseCase\Admin\NewEdit\Social;

use BaksDev\Settings\Main\Entity\Social\SettingsMainSocialInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class SettingsMainSocialDTO implements SettingsMainSocialInterface
{
	
	private ?string $icon;
	
	#[Assert\NotBlank]
	private ?string $title;
	
	#[Assert\NotBlank]
	#[Assert\Url]
	private ?string $href;
	
	
	/**
	 * @return string|null
	 */
	public function getIcon() : ?string
	{
		return $this->icon;
	}
	
	
	/**
	 * @param string|null $icon
	 */
	public function setIcon(?string $icon) : void
	{
		$this->icon = $icon;
	}
	
	
	/**
	 * @return string|null
	 */
	public function getTitle() : ?string
	{
		return $this->title;
	}
	
	
	/**
	 * @param string|null $title
	 */
	public function setTitle(?string $title) : void
	{
		$this->title = $title;
	}
	
	
	/**
	 * @return string|null
	 */
	public function getHref() : ?string
	{
		return $this->href;
	}
	
	
	/**
	 * @param string|null $href
	 */
	public function setHref(?string $href) : void
	{
		$this->href = $href;
	}
	
	
	
	//    /**
	//     * @param string|null $icon
	//     * @param string|null $title
	//     * @param string|null $href
	//     */
	//    public function __construct(string $icon = null, string $title = null, string $href = null)
	//    {
	//        $this->icon = $icon;
	//        $this->title = $title;
	//        $this->href = $href;
	//    }
	//
}

