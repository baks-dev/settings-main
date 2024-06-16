<?php

namespace BaksDev\Settings\Main\Type\Phone;

use App\Kernel;
use BaksDev\Core\Type\UidType\Uid;
use Symfony\Component\Uid\AbstractUid;

final class SettingsMainPhoneUid extends Uid
{
    public const TEST = '0188a9a4-963e-753a-8325-0305b85d8509';

    public const TYPE = 'settings_main_social';

}