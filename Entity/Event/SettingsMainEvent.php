<?php

namespace BaksDev\Settings\Main\Entity\Event;

use BaksDev\Core\Entity\EntityEvent;
use BaksDev\Core\Type\Modify\ModifyAction;
use BaksDev\Core\Type\Modify\Modify\ModifyActionNew;
use BaksDev\Core\Type\Modify\Modify\ModifyActionUpdate;
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
	public const TABLE = 'settings_main_event';
	
	/** ID события */
	#[ORM\Id]
	#[ORM\Column(type: SettingsMainEventUid::TYPE)]
	protected SettingsMainEventUid $id;
	
	/** ID SettingsMain */
	#[ORM\Column(type: SettingsMainIdentificator::TYPE)]
	protected ?SettingsMainIdentificator $setting = null;
	
	/** Модификатор */
	#[ORM\OneToOne(mappedBy: 'event', targetEntity: SettingsMainModify::class, cascade: ['all'])]
	protected SettingsMainModify $modify;
	
	/** Цвет */
	#[ORM\Column(name: 'color', type: Color::TYPE, nullable: false)]
	protected ?Color $color;
	
	/** Контактные телефоны */
	#[ORM\OneToMany(mappedBy: 'event', targetEntity: SettingsMainPhone::class, cascade: ['all'])]
	protected Collection $phone;
	
	/** Настройки SEO по умолчанию */
	#[ORM\OneToMany(mappedBy: 'event', targetEntity: SettingsMainSeo::class, cascade: ['all'])]
	protected Collection $seo;
	
	/** Социальные сети */
	#[ORM\OneToMany(mappedBy: 'event', targetEntity: SettingsMainSocial::class, cascade: ['all'])]
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

	public function getId() : SettingsMainEventUid
	{
		return $this->id;
	}

	public function getSetting() : ?SettingsMainIdentificator
	{
		return $this->setting;
	}
	

	public function setSetting(SettingsMain|SettingsMainIdentificator $setting) : void
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