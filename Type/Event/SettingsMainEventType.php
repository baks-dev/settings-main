<?php

namespace BaksDev\Settings\Main\Type\Event;

use BaksDev\Core\Type\UidType\UidType;

final class SettingsMainEventType extends UidType
{
	public function getClassType() : string
	{
		return SettingsMainEventUid::class;
	}
	
	
	public function getName() : string
	{
		return SettingsMainEventUid::TYPE;
	}
	
}