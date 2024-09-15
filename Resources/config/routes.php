<?php

use BaksDev\Settings\Main\BaksDevSettingsMainBundle;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {

    $MODULE = BaksDevSettingsMainBundle::PATH;

    $routes->import(
        $MODULE.'Controller',
        'attribute',
        false,
        $MODULE.implode(DIRECTORY_SEPARATOR, ['Controller', '**', '*Test.php'])
    )
        ->prefix(\BaksDev\Core\Type\Locale\Locale::routes())
        ->namePrefix('settings-main:');
};
