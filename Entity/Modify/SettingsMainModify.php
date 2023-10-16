<?php

namespace BaksDev\Settings\Main\Entity\Modify;

use BaksDev\Core\Entity\EntityEvent;
use BaksDev\Core\Type\Ip\IpAddress;
use BaksDev\Core\Type\Modify\ModifyAction;
use BaksDev\Core\Type\Modify\ModifyActionEnum;
use BaksDev\Settings\Main\Entity\Event\SettingsMainEvent;
use BaksDev\Users\User\Entity\User;
use BaksDev\Users\User\Type\Id\UserUid;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

/* Модификаторы событий SettingsMain */


#[ORM\Entity]
#[ORM\Table(name: 'settings_main_modify')]
#[ORM\Index(columns: ['action'])]
class SettingsMainModify extends EntityEvent
{
	public const TABLE = 'settings_main_modify';
	
	/** ID события */
	#[ORM\Id]
	#[ORM\OneToOne(inversedBy: 'modify', targetEntity: SettingsMainEvent::class)]
	#[ORM\JoinColumn(name: 'event', referencedColumnName: 'id')]
	protected SettingsMainEvent $event;
	
	/** Модификатор */
	#[ORM\Column(type: ModifyAction::TYPE, nullable: false)]
	protected ModifyAction $action;
	
	/** Дата */
	#[ORM\Column(name: 'mod_date', type: Types::DATETIME_IMMUTABLE, nullable: false)]
	private DateTimeImmutable $modDate;
	
	/** ID пользователя  */
	#[ORM\Column(type: UserUid::TYPE, nullable: true)]
	protected ?UserUid $usr = null;
	
	/** Ip адрес */
	#[ORM\Column(type: IpAddress::TYPE, nullable: false)]
	protected IpAddress $ip;
	
	/** User-agent */
	#[ORM\Column(type: Types::TEXT, nullable: false)]
	protected string $agent;
	
	
	public function __construct(SettingsMainEvent $event)
	{
		$this->event = $event;
		$this->modDate = new DateTimeImmutable();
		$this->ip = new IpAddress('127.0.0.1');
		$this->agent = 'console';
		$this->action = new ModifyAction(ModifyActionEnum::NEW);
	}
	
	
	public function __clone() : void
	{
		$this->modDate = new DateTimeImmutable();
		$this->action = new ModifyAction(ModifyActionEnum::UPDATE);
		$this->ip = new IpAddress('127.0.0.1');
		$this->agent = 'console';
	}

    public function __toString(): string
    {
        return (string) $this->event;
    }
	
	public function getDto($dto): mixed
	{
        $dto = is_string($dto) && class_exists($dto) ? new $dto() : $dto;

		if($dto instanceof SettingsMainModifyInterface)
		{
			return parent::getDto($dto);
		}
		
		throw new InvalidArgumentException(sprintf('Class %s interface error', $dto::class));
	}
	
	
	public function setEntity($dto): mixed
	{
		if($dto instanceof SettingsMainModifyInterface)
		{
			return parent::setEntity($dto);
		}
		
		throw new InvalidArgumentException(sprintf('Class %s interface error', $dto::class));
	}
	
	
	public function upModifyAgent(IpAddress $ip, ?string $agent) : void
	{
		$this->ip = $ip;
		$this->agent = $agent ?: 'console';
		$this->modDate = new DateTimeImmutable();
	}
	
	
	public function setUsr(UserUid|User|null $usr) : void
	{
		$this->usr = $usr instanceof User ? $usr->getId() : $usr;
	}
	
	
	public function equals(ModifyActionEnum $action) : bool
	{
		return $this->action->equals($action);
	}
	
}