<?php
/*
*  Copyright Baks.dev <admin@baks.dev>
*
*  Licensed under the Apache License, Version 2.0 (the "License");
*  you may not use this file except in compliance with the License.
*  You may obtain a copy of the License at
*
*  http://www.apache.org/licenses/LICENSE-2.0
*
*  Unless required by applicable law or agreed to in writing, software
*  distributed under the License is distributed on an "AS IS" BASIS,
*  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
*  See the License for the specific language governing permissions and
*   limitations under the License.
*
*/

namespace BaksDev\Settings\Main\Entity;


use BaksDev\Settings\Main\Entity\Event\SettingsMainEvent;
use BaksDev\Settings\Main\Type\Event\SettingsMainEventUid;
use BaksDev\Settings\Main\Type\Id\SettingsMainIdentificator;
use Doctrine\ORM\Mapping as ORM;

/* Системные настройки */

#[ORM\Entity]
#[ORM\Table(name: 'settings_main')]
class SettingsMain
{
    public const TABLE = 'settings_main';

    /** ID */
    #[ORM\Id]
    #[ORM\Column(type: SettingsMainIdentificator::TYPE)]
    private SettingsMainIdentificator $id;
    
    /** ID События */
    #[ORM\Column(name: 'event', type: SettingsMainEventUid::TYPE, unique: true, nullable: false)]
    private SettingsMainEventUid $event;
    

    public function __construct() { $this->id = new SettingsMainIdentificator();  }

    /**
    * @return SettingsMainIdentificator
    */
    public function getId() : SettingsMainIdentificator
    {
        return $this->id;
    }
    
    /**
     * @param SettingsMainEvent|\BaksDev\Settings\Main\Type\Event\SettingsMainEventUid $event
     */
    public function setEvent(SettingsMainEvent|SettingsMainEventUid $event) : void
    {
        $this->event = $event instanceof SettingsMainEvent ? $event->getId() : $event;;
    }
    
    /**
     * @return \BaksDev\Settings\Main\Type\Event\SettingsMainEventUid
     */
    public function getEvent() : SettingsMainEventUid
    {
        return $this->event;
    }
}
