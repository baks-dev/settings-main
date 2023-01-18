<?php

namespace BaksDev\Settings\Main\Entity\Event;

use BaksDev\Settings\Main\Type\Event\SettingsMainEventUid;

interface SettingsMainEventInterface
{
    public function getEvent() : ?SettingsMainEventUid;
    
    //public function setId(SettingsMainEventUid $id) : void;
}