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

//use App\Module\Files\Res\Upload\File\FileUploadInterface;
//use App\Module\Files\Res\Upload\Image\ImageUploadInterface;
//use App\Module\Products\Product\Entity\Event\ProductEventInterface;
//use App\Module\Products\Product\GetUserById\UniqProductUrl\UniqProductUrlInterface;
use BaksDev\Settings\Main\Entity as EntitySettingsMain;
use BaksDev\Settings\Main\Entity\Event\SettingsMainEventInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class SettingsMainHandler
{
    private EntityManagerInterface $entityManager;
    //private ImageUploadInterface $imageUpload;
    //private FileUploadInterface $fileUpload;
    //private UniqProductUrlInterface $uniqProductUrl;
    private ValidatorInterface $validator;
    private LoggerInterface $logger;
    
    public function __construct(
      EntityManagerInterface $entityManager,
      //      ImageUploadInterface $imageUpload,
      //      FileUploadInterface $fileUpload,
      //      UniqProductUrlInterface $uniqProductUrl,
      ValidatorInterface $validator,
      LoggerInterface $logger
    
    )
    {
        $this->entityManager = $entityManager;
        //        $this->imageUpload = $imageUpload;
        //        $this->fileUpload = $fileUpload;
        //        $this->uniqProductUrl = $uniqProductUrl;
        $this->validator = $validator;
        $this->logger = $logger;
    }
    
    public function handle(
      SettingsMainEventInterface $command
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
              $command->getEvent());
            
            if($EventRepo === null)
            {
                $uniqid = uniqid('', false);
                $errorsString = sprintf(
                  'Not found %s by id: %s',
                  EntitySettingsMain\Event\SettingsMainEvent::class,
                  $command->getEvent());
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
              ['event' => $command->getEvent()]);
            
            if(empty($SettingsMain))
            {
                $uniqid = uniqid('', false);
                $errorsString = sprintf(
                  'Not found %s by event: %s',
                  EntitySettingsMain\SettingsMain::class,
                  $command->getEvent());
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
        
        return $SettingsMain;
    }
    
}