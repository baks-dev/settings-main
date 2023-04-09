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

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use BaksDev\Orders\Order\Messenger\OrderMessage;
use Symfony\Config\FrameworkConfig;

return static function (ContainerConfigurator $configurator, FrameworkConfig $config)
{
    $services = $configurator->services()
      ->defaults()
      ->autowire()
      ->autoconfigure()
    ;
	
	$namespace = 'BaksDev\Settings\Main';
	
	/** Services */
	
	$services->load($namespace.'\Messenger\\', __DIR__.'/../../Messenger')
		->exclude('../../Messenger/**/*Message.php')
	;
	
	//$config->messenger()->routing(OrderMessage::class)->senders(['async_priority_high']);
	
};
