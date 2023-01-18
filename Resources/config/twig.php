<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use BaksDev\Core\Type\Field\FieldExtension;
use BaksDev\Core\Type\Ip\IpExtension;
use BaksDev\Core\Type\Measurement\MeasurementExtension;
use BaksDev\Core\Type\Modify\ModifyExtension;
use BaksDev\Core\Type\Money\MoneyExtension;
use BaksDev\Core\Type\Reference\ReferenceExtension;
use Symfony\Config\TwigConfig;

return static function (TwigConfig $config)
{
	$config->path(__DIR__.'/../view', 'SettingsMain');
};




