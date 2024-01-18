<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use BaksDev\Core\Repository\SettingsMain\SettingsMainInterface;
use BaksDev\Settings\Main\Repository\SettingsMain\SettingsMainRepository;

return static function(ContainerConfigurator $configurator) {

    $services = $configurator->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $NAMESPACE = 'BaksDev\Settings\Main\\';

    $MODULE = substr(__DIR__, 0, strpos(__DIR__, "Resources"));

    $services->load($NAMESPACE, $MODULE)
        ->exclude($MODULE.'{Entity,Resources,Type,*DTO.php,*Message.php}');

    $services->alias(SettingsMainInterface::class.' $settingsMain', SettingsMainRepository::class);

};
