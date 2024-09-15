<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use BaksDev\Settings\Main\BaksDevSettingsMainBundle;
use BaksDev\Settings\Main\Type\Event\SettingsMainEventType;
use BaksDev\Settings\Main\Type\Event\SettingsMainEventUid;
use BaksDev\Settings\Main\Type\Id\SettingsMainIdentificator;
use BaksDev\Settings\Main\Type\Id\SettingsMainType;
use BaksDev\Settings\Main\Type\Phone\SettingsMainPhoneType;
use BaksDev\Settings\Main\Type\Phone\SettingsMainPhoneUid;
use BaksDev\Settings\Main\Type\Social\SettingsMainSocialType;
use BaksDev\Settings\Main\Type\Social\SettingsMainSocialUid;
use Symfony\Config\DoctrineConfig;

return static function (ContainerConfigurator $container, DoctrineConfig $doctrine) {

    $doctrine->dbal()->type(SettingsMainIdentificator::TYPE)->class(SettingsMainType::class);
    $doctrine->dbal()->type(SettingsMainEventUid::TYPE)->class(SettingsMainEventType::class);
    $doctrine->dbal()->type(SettingsMainPhoneUid::TYPE)->class(SettingsMainPhoneType::class);
    $doctrine->dbal()->type(SettingsMainSocialUid::TYPE)->class(SettingsMainSocialType::class);

    $emDefault = $doctrine->orm()->entityManager('default')->autoMapping(true);


    $emDefault->mapping('settings-main')
        ->type('attribute')
        ->dir(BaksDevSettingsMainBundle::PATH.'Entity')
        ->isBundle(false)
        ->prefix(BaksDevSettingsMainBundle::NAMESPACE.'\\Entity')
        ->alias('settings-main');

};
