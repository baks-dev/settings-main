<?php

namespace BaksDev\Settings\Main\Entity\Event;

use BaksDev\Reference\Color\Type\Color;

use BaksDev\Settings\Main\Entity\Modify\SettingsMainModify;
use BaksDev\Settings\Main\Entity\Phone\SettingsMainPhone;
use BaksDev\Settings\Main\Entity\Seo\SettingsMainSeo;
use BaksDev\Settings\Main\Entity\SettingsMain;
use BaksDev\Settings\Main\Entity\Social\SettingsMainSocial;
use BaksDev\Settings\Main\Type\Event\SettingsMainEventUid;
use BaksDev\Settings\Main\Type\Id\SettingsMainIdentificator;

use BaksDev\Core\Entity\EntityEvent;
use BaksDev\Core\Type\Modify\ModifyAction;
use BaksDev\Core\Type\Modify\ModifyActionEnum;

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
		$this->modify = new SettingsMainModify($this, new ModifyAction(ModifyActionEnum::NEW));
	}
	
	
	public function __clone()
	{
		$this->id = new SettingsMainEventUid();
	}
	
	
	/**
	 * @return SettingsMainEventUid
	 */
	public function getId() : SettingsMainEventUid
	{
		return $this->id;
	}
	
	
	/**
	 * @return ?SettingsMainIdentificator
	 */
	public function getSetting() : ?SettingsMainIdentificator
	{
		return $this->setting;
	}
	
	
	/** Присваиваем идентификатор агрегата */
	
	public function setSetting(SettingsMain|SettingsMainIdentificator $setting) : void
	{
		$this->setting = $setting instanceof SettingsMain ? $setting->getId() : $setting;
	}
	
	
	/** Сверяем статус модификатора события */
	
	public function isModifyActionEquals(ModifyActionEnum $action) : bool
	{
		return $this->modify->equals($action);
	}
	
	
	/** Присваиваем свойствам DTO значения из объекта сущности */
	
	public function getDto($dto) : mixed
	{
		if($dto instanceof SettingsMainEventInterface)
		{
			return parent::getDto($dto);
		}
		
		throw new InvalidArgumentException(sprintf('Class %s interface error', $dto::class));
	}
	
	
	/** Присваиваем свойствам сущности значения из объекта DTO */
	
	public function setEntity($dto) : mixed
	{
		if($dto instanceof SettingsMainEventInterface)
		{
			return parent::setEntity($dto);
		}
		
		throw new InvalidArgumentException(sprintf('Class %s interface error', $dto::class));
	}
	
}