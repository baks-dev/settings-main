<?php
/*
 *  Copyright 2024.  Baks.dev <admin@baks.dev>
 *  
 *  Permission is hereby granted, free of charge, to any person obtaining a copy
 *  of this software and associated documentation files (the "Software"), to deal
 *  in the Software without restriction, including without limitation the rights
 *  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *  copies of the Software, and to permit persons to whom the Software is furnished
 *  to do so, subject to the following conditions:
 *  
 *  The above copyright notice and this permission notice shall be included in all
 *  copies or substantial portions of the Software.
 *  
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *  FITNESS FOR A PARTICULAR PURPOSE AND NON INFRINGEMENT. IN NO EVENT SHALL THE
 *  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 *  THE SOFTWARE.
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

final readonly class SettingsMainRepository implements SettingsMainInterface
{
    public function __construct(
        private DBALQueryBuilder $DBALQueryBuilder,
        private SettingsMainIdentificator $settingsMainIdentificator
    ) {}

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
        return $dbal
            ->enableCache('settings-main', 84600)
            ->fetchAssociative() ?: [];

    }


    public function getPhone(): array
    {
        $dbal = $this->DBALQueryBuilder->createQueryBuilder(self::class);


        $dbal
            ->from(SettingsMain::class, 'main')
            ->where('main.id = :main')
            ->setParameter('main', $this->settingsMainIdentificator, SettingsMainIdentificator::TYPE);


        $dbal
            ->addSelect('phone.icon')
            ->addSelect('phone.title')
            ->addSelect('phone.phone')
            ->join(
                'main',
                SettingsMainPhone::class,
                'phone',
                'phone.event = main.event'
            );


        return $dbal->fetchAllAssociative();

    }

    public function getSocial()
    {
        $dbal = $this->DBALQueryBuilder->createQueryBuilder(self::class);

        $dbal
            ->from(SettingsMain::class, 'main')
            ->where('main.id = :main')
            ->setParameter('main', $this->settingsMainIdentificator, SettingsMainIdentificator::TYPE);

        $dbal
            ->addSelect('social.href')
            ->addSelect('social.icon')
            ->addSelect('social.title')
            ->join(
                'main',
                SettingsMainSocial::class,
                'social',
                'social.event = main.event'
            );


        return $dbal->fetchAllAssociative();

    }

}