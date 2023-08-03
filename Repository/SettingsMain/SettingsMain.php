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

use BaksDev\Core\Doctrine\DBALQueryBuilder;
use BaksDev\Core\Type\Locale\Locale;
use BaksDev\Settings\Main\Entity as EntitySettings;
use BaksDev\Settings\Main\Type\Id\SettingsMainIdentificator;

final class SettingsMain implements SettingsMainInterface
{
    private SettingsMainIdentificator $settingsMainIdentificator;
    private DBALQueryBuilder $DBALQueryBuilder;

    public function __construct(
        DBALQueryBuilder $DBALQueryBuilder,
    )
    {

        $this->settingsMainIdentificator = new SettingsMainIdentificator();
        $this->DBALQueryBuilder = $DBALQueryBuilder;
    }


    public function getSettingsMainAssociative(string $domain, string $locale): bool|array
    {

        $qb = $this->DBALQueryBuilder->createQueryBuilder(self::class);

        $qb->setParameter('local', new Locale($locale), Locale::TYPE);

        $qb->addSelect('event.color');
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
            'seo.event = main.event and seo.local = :local'
        );


        $qb->where('main.id = :main');
        $qb->setParameter('main', $this->settingsMainIdentificator, SettingsMainIdentificator::TYPE);


        /* Кешируем результат DBAL */
        return $qb->enableCache('SettingsMain', 84600)->fetchAssociative();

    }


    public function getPhone()
    {
        $qb = $this->DBALQueryBuilder->createQueryBuilder(self::class);

        $qb->select('phone.icon');
        $qb->addSelect('phone.title');
        $qb->addSelect('phone.phone');
        //$qb->addSelect('phone.phone_format');

        $qb->from(EntitySettings\SettingsMain::TABLE, 'main');

        $qb->join('main', EntitySettings\Phone\SettingsMainPhone::TABLE, 'phone', 'phone.event = main.event');

        $qb->where('main.id = :main');
        $qb->setParameter('main', $this->settingsMainIdentificator, SettingsMainIdentificator::TYPE);

        return $qb->fetchAllAssociative();

    }
    
    public function getSocial()
    {
        $qb = $this->DBALQueryBuilder->createQueryBuilder(self::class);

        $qb->select('social.href');
        $qb->addSelect('social.icon');
        $qb->addSelect('social.title');

        $qb->from(EntitySettings\SettingsMain::TABLE, 'main');

        $qb->join('main', EntitySettings\Social\SettingsMainSocial::TABLE, 'social', 'social.event = main.event');

        $qb->where('main.id = :main');
        $qb->setParameter('main', $this->settingsMainIdentificator, SettingsMainIdentificator::TYPE);

        return $qb->fetchAllAssociative();

    }

}