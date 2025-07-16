<?php
/*
 *  Copyright 2025.  Baks.dev <admin@baks.dev>
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
use BaksDev\Field\Pack\Phone\Type\PhoneField;
use BaksDev\Settings\Main\Entity\Event\SettingsMainEvent;
use BaksDev\Settings\Main\Entity\Phone\SettingsMainPhone;
use BaksDev\Settings\Main\Entity\Seo\SettingsMainSeo;
use BaksDev\Settings\Main\Entity\SettingsMain;
use BaksDev\Settings\Main\Entity\Social\SettingsMainSocial;
use BaksDev\Settings\Main\Type\Id\SettingsMainIdentificator;
use BaksDev\Users\Profile\TypeProfile\Entity\Section\Fields\TypeProfileSectionField;
use BaksDev\Users\Profile\TypeProfile\Entity\Section\TypeProfileSection;
use BaksDev\Users\Profile\TypeProfile\Type\Section\Field\Id\TypeProfileSectionFieldUid;
use BaksDev\Users\Profile\UserProfile\Entity\Event\Personal\UserProfilePersonal;
use BaksDev\Users\Profile\UserProfile\Entity\Event\Value\UserProfileValue;
use BaksDev\Users\Profile\UserProfile\Entity\UserProfile;
use BaksDev\Users\Profile\UserProfile\Type\Id\UserProfileUid;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final readonly class SettingsMainRepository implements SettingsMainInterface
{
    public function __construct(
        private DBALQueryBuilder $DBALQueryBuilder,
        private SettingsMainIdentificator $settingsMainIdentificator,
        #[Autowire(env: 'PROJECT_PROFILE')] private ?string $projectProfile = null,
    ) {}

    public function getSettingsMainAssociative(): ?array
    {
        $dbal = $this
            ->DBALQueryBuilder
            ->createQueryBuilder(self::class)
            ->bindLocal();

        /**
         * Присваиваем контактный номер телефона профиля проекта
         */

        if($this->projectProfile)
        {
            $dbal
                ->from(UserProfile::class, 'profile')
                ->where('profile.id = :'.$dbal::PROJECT_PROFILE_KEY)
                ->setParameter(
                    key: $dbal::PROJECT_PROFILE_KEY,
                    value: new UserProfileUid($this->projectProfile),
                    type: UserProfileUid::TYPE,
                );

            $dbal
                ->addSelect('profile_personal.username AS title')
                ->addSelect('NULL AS keywords')
                ->addSelect('profile_personal.location AS description')
                ->leftJoin(
                    'profile',
                    UserProfilePersonal::class,
                    'profile_personal',
                    'profile_personal.event = profile.event',
                );

            return $dbal
                ->enableCache('users-profile-user', 84600)
                ->fetchAssociative() ?: [];
        }


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
                'event.id = main.event',
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
                'seo.event = main.event and seo.local = :local',
            );


        /* Кешируем результат DBAL */
        return $dbal
            ->enableCache('settings-main', 84600)
            ->fetchAssociative() ?: [];

    }


    public function getPhone(): array
    {
        $dbal = $this->DBALQueryBuilder->createQueryBuilder(self::class);

        /**
         * Присваиваем контактный номер телефона профиля проекта
         */

        if($this->projectProfile)
        {
            $dbal
                ->from(UserProfile::class, 'profile')
                ->where('profile.id = :'.$dbal::PROJECT_PROFILE_KEY)
                ->setParameter(
                    key: $dbal::PROJECT_PROFILE_KEY,
                    value: new UserProfileUid($this->projectProfile),
                    type: UserProfileUid::TYPE,
                );

            $dbal
                ->addSelect('NULL AS icon')
                ->addSelect('NULL AS title')
                ->addSelect('profile_value.value AS phone')
                ->leftJoin(
                    'profile',
                    UserProfileValue::class,
                    'profile_value',
                    'profile_value.event = profile.event',
                );

            $dbal
                ->join(
                    'profile_value',
                    TypeProfileSectionField::class,
                    'type_section_field',
                    '
                        type_section_field.id = profile_value.field AND
                        type_section_field.type = :field_phone
                    ')
                ->setParameter(
                    'field_phone',
                    PhoneField::TYPE,
                );

            return $dbal
                ->enableCache('users-profile-user', 84600)
                ->fetchAllAssociative();
        }


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
                'phone.event = main.event',
            );


        return $dbal
            ->enableCache('settings-main', '1 day')
            ->fetchAllAssociative();

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
                'social.event = main.event',
            );


        return $dbal
            ->enableCache('settings-main', '1 day')
            ->fetchAllAssociative();

    }

}