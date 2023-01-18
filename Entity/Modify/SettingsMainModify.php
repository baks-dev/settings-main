<?php

namespace BaksDev\Settings\Main\Entity\Modify;

use BaksDev\Settings\Main\Entity\Event\SettingsMainEvent;
use App\Module\Users\User\Entity\User;
use App\Module\Users\User\Type\Id\UserUid;
use BaksDev\Core\Entity\EntityEvent;
use BaksDev\Core\Type\Ip\IpAddress;
use BaksDev\Core\Type\Modify\ModifyAction;
use BaksDev\Core\Type\Modify\ModifyActionEnum;
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
    #[ORM\Column(name: 'user_id', type: UserUid::TYPE, nullable: true)]
    protected ?UserUid $user = null;
    
    /** Ip адрес */
    #[ORM\Column(name: 'user_ip', type: IpAddress::TYPE, nullable: false)]
    protected IpAddress $ipAddress;
    
    /** User-agent */
    #[ORM\Column(name: 'user_agent', type: Types::TEXT, nullable: false)]
    protected string $userAgent;
    
    public function __construct(SettingsMainEvent $event)
    {
        $this->event = $event;
        $this->modDate = new DateTimeImmutable();
        $this->ipAddress = new IpAddress('127.0.0.1');
        $this->userAgent = 'console';
		$this->action = new ModifyAction(ModifyActionEnum::NEW);
    }
    
    public function __clone() : void
    {
        $this->modDate = new DateTimeImmutable();
        $this->action = new ModifyAction(ModifyActionEnum::UPDATE);
        $this->ipAddress = new IpAddress('127.0.0.1');
        $this->userAgent = 'console';
    }
    
    public function getDto($dto) : mixed
    {
        if($dto instanceof SettingsMainModifyInterface)
        {
            return parent::getDto($dto);
        }
        
        throw new InvalidArgumentException(sprintf('Class %s interface error', $dto::class));
    }
    
    public function setEntity($dto) : mixed
    {
        if($dto instanceof SettingsMainModifyInterface)
        {
            return parent::setEntity($dto);
        }
        
        throw new InvalidArgumentException(sprintf('Class %s interface error', $dto::class));
    }
    
    public function upModifyAgent(IpAddress $ipAddress, string $userAgent) : void
    {
        $this->ipAddress = $ipAddress;
        $this->userAgent = $userAgent;
        $this->modDate = new DateTimeImmutable();
    }
    
    public function setUser(UserUid|User|null $user) : void
    {
        $this->user = $user instanceof User ? $user->getId() : $user;
    }
    
    public function equals(ModifyActionEnum $action) : bool
    {
        return $this->action->equals($action);
    }
    
}