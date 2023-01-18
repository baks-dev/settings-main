<?php

namespace BaksDev\Settings\Main\Type\Event;

use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV7;

final class SettingsMainEventUid
{
    public const TYPE = 'settings_main_event_id';
    
    private Uuid $value;
    
    public function __construct(AbstractUid|string|null $value = null)
    {
        if($value === null)
        {
            $value = Uuid::v7();
        }
        
        else if(is_string($value))
        {
            $value = new UuidV7($value);
        }
        
        $this->value = $value;
    }
    
    public function __toString() : string
    {
        return $this->value;
    }
    
    public function getValue() : AbstractUid
    {
        return $this->value;
    }
    
}