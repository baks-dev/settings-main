<?php

namespace BaksDev\Settings\Main\Type\Phone;

use BaksDev\Core\Type\UidType\UidType;

final class SettingsMainPhoneType extends UidType
{
	public function getClassType() : string
	{
		return SettingsMainPhoneUid::class;
	}
	
	
	public function getName() : string
	{
		return SettingsMainPhoneUid::TYPE;
	}
	
}
