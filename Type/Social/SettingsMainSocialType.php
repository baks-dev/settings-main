<?php

namespace BaksDev\Settings\Main\Type\Social;

use BaksDev\Core\Type\UidType\UidType;

final class SettingsMainSocialType extends UidType
{
    public function getClassType() : string
    {
        return SettingsMainSocialUid::class;
    }
    
    public function getName() : string
    {
        return SettingsMainSocialUid::TYPE;
    }
}
