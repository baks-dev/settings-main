<?php

namespace BaksDev\Settings\Main\Entity\Modify;

use BaksDev\Core\Type\Ip\IpAddress;
use BaksDev\Core\Type\Modify\Modify\ModifyActionNew;
use BaksDev\Core\Type\Modify\Modify\ModifyActionUpdate;
use BaksDev\Users\User\Entity\User;
use BaksDev\Users\User\Type\Id\UserUid;

interface SettingsMainModifyInterface
{
	public function upModifyAgent(IpAddress $ip, ?string $agent) : void;
	
	
	public function setUsr(UserUid|User|null $usr) : void;
	
	
	public function equals(mixed $action) : bool;
	
}