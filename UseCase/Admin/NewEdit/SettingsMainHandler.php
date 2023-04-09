<?php
/*
 * Copyright (c) 2022.  Baks.dev <admin@baks.dev>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *  http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace BaksDev\Settings\Main\UseCase\Admin\NewEdit;

use BaksDev\Settings\Main\Entity as EntitySettingsMain;
use BaksDev\Settings\Main\Entity\Event\SettingsMainEventInterface;
use BaksDev\Settings\Main\Messenger\SettingsMainMessage;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class SettingsMainHandler
{
	private EntityManagerInterface $entityManager;
	
	private ValidatorInterface $validator;
	
	private LoggerInterface $logger;
	
	private MessageBusInterface $bus;
	
	
	public function __construct(
		EntityManagerInterface $entityManager,
		ValidatorInterface $validator,
		LoggerInterface $logger,
		MessageBusInterface $bus,
	
	)
	{
		$this->entityManager = $entityManager;
		$this->validator = $validator;
		$this->logger = $logger;
		$this->bus = $bus;
	}
	
	
	public function handle(
		SettingsMainEventInterface $command,
	) : EntitySettingsMain\SettingsMain|string
	{
		
		/* Валидация */
		$errors = $this->validator->validate($command);
		
		if(count($errors) > 0)
		{
			$uniqid = uniqid('', false);
			$errorsString = (string) $errors;
			$this->logger->error($uniqid.': '.$errorsString);
			
			return $uniqid;
		}
		
		if($command->getEvent())
		{
			$EventRepo = $this->entityManager->getRepository(EntitySettingsMain\Event\SettingsMainEvent::class)->find(
				$command->getEvent()
			);
			
			if($EventRepo === null)
			{
				$uniqid = uniqid('', false);
				$errorsString = sprintf(
					'Not found %s by id: %s',
					EntitySettingsMain\Event\SettingsMainEvent::class,
					$command->getEvent()
				);
				$this->logger->error($uniqid.': '.$errorsString);
				
				return $uniqid;
			}
			
			$Event = $EventRepo->cloneEntity();
		}
		else
		{
			$Event = new EntitySettingsMain\Event\SettingsMainEvent();
		}
		
		$Event->setEntity($command);
		$this->entityManager->clear();
		//$this->entityManager->refresh();
		$this->entityManager->persist($Event);
		
		//
		//        dump($command);
		//        dd($Event);
		
		/** @var EntitySettingsMain\SettingsMain $SettingsMain */
		if($Event->getSetting())
		{
			$SettingsMain = $this->entityManager->getRepository(EntitySettingsMain\SettingsMain::class)->findOneBy(
				['event' => $command->getEvent()]
			);
			
			if(empty($SettingsMain))
			{
				$uniqid = uniqid('', false);
				$errorsString = sprintf(
					'Not found %s by event: %s',
					EntitySettingsMain\SettingsMain::class,
					$command->getEvent()
				);
				$this->logger->error($uniqid.': '.$errorsString);
				
				return $uniqid;
			}
		}
		else
		{
			$SettingsMain = new EntitySettingsMain\SettingsMain();
			$this->entityManager->persist($SettingsMain);
			$Event->setSetting($SettingsMain);
		}
		
		$SettingsMain->setEvent($Event); /* Обновляем событие */
		
		//dd($this->entityManager->getUnitOfWork());
		
		$this->entityManager->flush();
		
		/* Отправляем собыие в шину  */
		$this->bus->dispatch(new SettingsMainMessage($SettingsMain->getId(), $SettingsMain->getEvent(), $command->getEvent()));
		
		
		return $SettingsMain;
	}
	
}