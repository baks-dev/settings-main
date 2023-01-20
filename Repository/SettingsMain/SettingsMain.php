<?php
/*
 *  Copyright 2022.  Baks.dev <admin@baks.dev>
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

namespace BaksDev\Settings\Main\Repository\SettingsMain;

use BaksDev\Core\Repository\SettingsMain\SettingsMainInterface;
use BaksDev\Settings\Main\Entity as EntitySettings;
use BaksDev\Settings\Main\Type\Id\SettingsMainIdentificator;
use BaksDev\Core\Type\Locale\Locale;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsDecorator(SettingsMainInterface::class)]
final class SettingsMain implements SettingsMainInterface
{
    private Connection $connection;
    private Locale $locale;
    private SettingsMainIdentificator $settingsMainIdentificator;
	private SettingsMainInterface $settingsMain;

    public function __construct(SettingsMainInterface $settingsMain, Connection $connection, TranslatorInterface $translator)
    {
		$this->settingsMain = $settingsMain;

        $this->connection = $connection;
        $this->locale = new Locale($translator->getLocale());
        $this->settingsMainIdentificator = new SettingsMainIdentificator();
    }
	
	public function getSettingsMainAssociative() : array
    {
        $qb = $this->connection->createQueryBuilder();
        
        $qb->select('event.color');
        
        // $qb->select('*');
        
        $qb->from(EntitySettings\SettingsMain::TABLE, 'main');
		
        
        $qb->join('main', EntitySettings\Event\SettingsMainEvent::TABLE, 'event', 'event.id = main.event');
		
        
        /* SEO */
        $qb->addSelect('seo.title');
        $qb->addSelect('seo.keywords');
        $qb->addSelect('seo.description');
        $qb->join(
          'main',
          EntitySettings\Seo\SettingsMainSeo::TABLE,
          'seo',
          'seo.event = main.event and seo.local = :local');
        $qb->setParameter('local', $this->locale, Locale::TYPE);
        
        $qb->where('main.id = :main');
        $qb->setParameter('main', $this->settingsMainIdentificator, SettingsMainIdentificator::TYPE);
    
        
        $settings = $qb->fetchAssociative();
		
        if(is_array($settings))
        {
            $settings['phone'] = $this->getPhone();
            $settings['social'] = $this->getSocial();
        }
		
        return $settings ?: $this->settingsMain->getSettingsMainAssociative();
    }
    
    
    public function getPhone()
    {
        $qb = $this->connection->createQueryBuilder();
        
        $qb->select('phone.icon');
        $qb->addSelect('phone.title');
        $qb->addSelect('phone.phone');
        //$qb->addSelect('phone.phone_format');
        
        $qb->from(EntitySettings\SettingsMain::TABLE, 'main');
        
        $qb->leftJoin('main', EntitySettings\Phone\SettingsMainPhone::TABLE, 'phone', 'phone.event = main.event');
        
        $qb->where('main.id = :main');
        $qb->setParameter('main', $this->settingsMain, SettingsMainIdentificator::TYPE);
        
        return $qb->fetchAllAssociative();
        
    }
    
    public function getSocial()
    {
        $qb = $this->connection->createQueryBuilder();
        
        $qb->select('social.href');
        $qb->addSelect('social.icon');
        $qb->addSelect('social.title');
        
        $qb->from(EntitySettings\SettingsMain::TABLE, 'main');
        
        $qb->join('main', EntitySettings\Social\SettingsMainSocial::TABLE, 'social', 'social.event = main.event');
        
        $qb->where('main.id = :main');
        $qb->setParameter('main', $this->settingsMain, SettingsMainIdentificator::TYPE);
        
        return $qb->fetchAllAssociative();
        
    }
    
}