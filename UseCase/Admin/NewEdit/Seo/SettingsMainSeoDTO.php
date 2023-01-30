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

namespace BaksDev\Settings\Main\UseCase\Admin\NewEdit\Seo;

use BaksDev\Settings\Main\Entity\Seo\SettingsMainSeoInterface;
use BaksDev\Core\Type\Locale\Locale;
use Symfony\Component\Validator\Constraints as Assert;

final class SettingsMainSeoDTO implements SettingsMainSeoInterface
{
	
	/** Локаль  */
	#[Assert\NotBlank]
	#[Assert\Locale]
	private readonly ?Locale $local;
	
	/** Title  */
	#[Assert\NotBlank]
	#[Assert\Regex(pattern: '/^[\w \.\,\_\-\(\)\%]+$/iu')]
	private ?string $title = null;
	
	/** Keywords */
	#[Assert\NotBlank]
	#[Assert\Regex(pattern: '/^[\w \.\,\_\-\(\)\%]+$/iu')]
	private ?string $keywords = null;
	
	/** Description */
	#[Assert\NotBlank]
	#[Assert\Regex(pattern: '/^[\w \.\,\_\-\(\)\%]+$/iu')]
	private ?string $description = null;
	
	/** Локаль  */
	/**
	 * @return Locale
	 */
	public function getLocal() : Locale
	{
		return $this->local;
	}
	
	
	/**
	 * @param string|Locale $local
	 */
	public function setLocal(string $local) : void
	{
		$this->local = new Locale($local);
	}
	
	
	/** Title  */
	
	public function getTitle() : ?string
	{
		return $this->title;
	}
	
	
	public function setTitle(?string $title) : void
	{
		$this->title = $title;
	}
	
	
	/** Keywords */
	
	public function getKeywords() : ?string
	{
		return $this->keywords;
	}
	
	
	public function setKeywords(?string $keywords) : void
	{
		$this->keywords = $keywords;
	}
	
	
	/** Description */
	
	public function getDescription() : ?string
	{
		return $this->description;
	}
	
	
	public function setDescription(?string $description) : void
	{
		$this->description = $description;
	}
	
}