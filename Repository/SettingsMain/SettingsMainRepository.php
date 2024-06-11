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
use BaksDev\Core\Repository\SettingsMain\SettingsMainInterface;
use BaksDev\Settings\Main\Entity\Event\SettingsMainEvent;
use BaksDev\Settings\Main\Entity\Phone\SettingsMainPhone;
use BaksDev\Settings\Main\Entity\Seo\SettingsMainSeo;
use BaksDev\Settings\Main\Entity\SettingsMain;
use BaksDev\Settings\Main\Entity\Social\SettingsMainSocial;
use BaksDev\Settings\Main\Type\Id\SettingsMainIdentificator;

final class SettingsMainRepository implements SettingsMainInterface
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

    public function getSettingsMainAssociative(): ?array
    {
        $dbal = $this
            ->DBALQueryBuilder
            ->createQueryBuilder(self::class)
            ->bindLocal();


        $dbal
            ->from(SettingsMain::class, 'main')
            ->where('main.id = :main')
            ->setParameter('main', $this->settingsMainIdentificator, SettingsMainIdentificator::TYPE);

        $dbal
            ->addSelect('event.color')
            ->leftJoin(
                'main',
                SettingsMainEvent::class,
                'event',
                'event.id = main.event'
            );

        /* SEO */
        $dbal
            ->addSelect('seo.title')
            ->addSelect('seo.keywords')
            ->addSelect('seo.description')
            ->leftJoin(
                'main',
                SettingsMainSeo::class,
                'seo',
                'seo.event = main.event and seo.local = :local'
            );


        /* Кешируем результат DBAL */
        return $dbal->enableCache('settings-main', 84600)->fetchAssociative() ?: [];

    }


    public function getPhone(): array
    {
        $dbal = $this->DBALQueryBuilder->createQueryBuilder(self::class);


        $dbal
            ->from(SettingsMain::TABLE, 'main')
            ->where('main.id = :main')
            ->setParameter('main', $this->settingsMainIdentificator, SettingsMainIdentificator::TYPE);


        $dbal
            ->addSelect('phone.icon')
            ->addSelect('phone.title')
            ->addSelect('phone.phone')
            ->join(
                'main',
                SettingsMainPhone::TABLE,
                'phone',
                'phone.event = main.event'
            );


        return $dbal->fetchAllAssociative();

    }

    public function getSocial()
    {
        $dbal = $this->DBALQueryBuilder->createQueryBuilder(self::class);

        $dbal
            ->from(SettingsMain::TABLE, 'main')
            ->where('main.id = :main')
            ->setParameter('main', $this->settingsMainIdentificator, SettingsMainIdentificator::TYPE);

        $dbal
            ->addSelect('social.href')
            ->addSelect('social.icon')
            ->addSelect('social.title')
            ->join(
                'main',
                SettingsMainSocial::TABLE,
                'social',
                'social.event = main.event'
            );


        return $dbal->fetchAllAssociative();

    }

}