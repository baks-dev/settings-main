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

namespace BaksDev\Settings\Main\Entity\Seo;

use BaksDev\Core\Entity\EntityEvent;
use BaksDev\Core\Type\Locale\Locale;
use BaksDev\Settings\Main\Entity\Event\SettingsMainEvent;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

#[ORM\Entity]
#[ORM\Table(name: 'settings_main_seo')]
class SettingsMainSeo extends EntityEvent
{
	public const TABLE = "settings_main_seo";
	
	/** Связь на событие */
	#[ORM\Id]
	#[ORM\ManyToOne(targetEntity: SettingsMainEvent::class, inversedBy: "seo")]
	#[ORM\JoinColumn(name: 'event', referencedColumnName: "id")]
	protected SettingsMainEvent $event;
	
	/** Локаль */
	#[ORM\Id]
	#[ORM\Column(name: 'local', type: Locale::TYPE, length: 2, nullable: false)]
	protected Locale $local;
	
	/** Шаблон META TITLE */
	#[ORM\Column(name: 'title', type: Types::TEXT, nullable: false)]
	protected string $title;
	
	/** Шаблон META KEYWORDS */
	#[ORM\Column(name: 'keywords', type: Types::TEXT, nullable: true)]
	protected ?string $keywords;
	
	/** Шаблон META DESCRIPTION */
	#[ORM\Column(name: 'description', type: Types::TEXT, nullable: true)]
	protected ?string $description;
	
	
	public function __construct(SettingsMainEvent $event)
	{
		$this->event = $event;
	}

    public function __toString(): string
    {
        return (string) $this->event;
    }
	

	public function getDto($dto): mixed
	{
        $dto = is_string($dto) && class_exists($dto) ? new $dto() : $dto;

		if($dto instanceof SettingsMainSeoInterface)
		{
			return parent::getDto($dto);
		}
		
		throw new InvalidArgumentException(sprintf('Class %s interface error', $dto::class));
	}

	public function setEntity($dto): mixed
	{
		if($dto instanceof SettingsMainSeoInterface)
		{
			return parent::setEntity($dto);
		}
		
		throw new InvalidArgumentException(sprintf('Class %s interface error', $dto::class));
	}
	
}