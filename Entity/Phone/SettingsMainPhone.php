<?php

namespace BaksDev\Settings\Main\Entity\Phone;

use BaksDev\Settings\Main\Entity\Event\SettingsMainEvent;
use BaksDev\Settings\Main\Type\Phone\SettingsMainPhoneUid;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

use BaksDev\Core\Entity\EntityEvent;
use BaksDev\Core\Type\Modify\ModifyAction;
use BaksDev\Core\Type\Modify\ModifyActionEnum;

use Exception;
use InvalidArgumentException;

/* SettingsMainPhone */


#[ORM\Entity]
#[ORM\Table(name: 'settings_main_phone')]
class SettingsMainPhone extends EntityEvent
{
	public const TABLE = 'settings_main_phone';
	
	/** ID */
	#[ORM\Id]
	#[ORM\Column(type: SettingsMainPhoneUid::TYPE)]
	private SettingsMainPhoneUid $id;
	
	/** Связь на событие  */
	#[ORM\ManyToOne(targetEntity: SettingsMainEvent::class, inversedBy: "phone")]
	#[ORM\JoinColumn(name: 'event', referencedColumnName: 'id')]
	private SettingsMainEvent $event;
	
	/** Иконка оператора */
	#[ORM\Column(name: 'icon', type: Types::STRING, nullable: true)]
	private ?string $icon;
	
	/** Краткое описание */
	#[ORM\Column(name: 'title', type: Types::STRING, nullable: true)]
	private ?string $title;
	
	/** Номер телефона */
	#[ORM\Column(name: 'phone', type: Types::STRING, nullable: false)]
	private string $phone;
	
	
	public function __construct(SettingsMainEvent $event)
	{
		$this->id = new SettingsMainPhoneUid();
		$this->event = $event;
	}
	
	
	public function __clone()
	{
		$this->id = new SettingsMainPhoneUid();
	}
	
	
	/**
	 * @return SettingsMainPhoneUid
	 */
	public function getId() : SettingsMainPhoneUid
	{
		return $this->id;
	}
	
	
	/** Присваиваем свойствам DTO значения из объекта сущности */
	
	public function getDto($dto) : mixed
	{
		if($dto instanceof SettingsMainPhoneInterface)
		{
			return parent::getDto($dto);
		}
		
		throw new InvalidArgumentException(sprintf('Class %s interface error', $dto::class));
	}
	
	
	/** Присваиваем свойствам сущности значения из объекта DTO */
	
	public function setEntity($dto) : mixed
	{
		if($dto instanceof SettingsMainPhoneInterface)
		{
			return parent::setEntity($dto);
		}
		
		throw new InvalidArgumentException(sprintf('Class %s interface error', $dto::class));
	}
	
}