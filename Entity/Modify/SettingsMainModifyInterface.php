<?php

namespace BaksDev\Settings\Main\Entity\Modify;

use BaksDev\Users\User\Entity\User;
use BaksDev\Users\User\Type\Id\UserUid;
use BaksDev\Core\Type\Ip\IpAddress;
use BaksDev\Core\Type\Modify\ModifyActionEnum;

interface SettingsMainModifyInterface
{
	public function upModifyAgent(IpAddress $ipAddress, string $userAgent) : void;
	
	
	public function setUser(UserUid|User|null $user) : void;
	
	
	public function equals(ModifyActionEnum $action) : bool;
	
}