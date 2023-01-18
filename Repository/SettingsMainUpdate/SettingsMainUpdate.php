<?php

namespace BaksDev\Settings\Main\Repository\SettingsMainUpdate;

use BaksDev\Settings\Main\Entity as EntitySettingsMain;
use BaksDev\Settings\Main\Type\Id\SettingsMainIdentificator;
use Doctrine\ORM\EntityManagerInterface;

final class SettingsMainUpdate implements SettingsMainUpdateInterface
{
    
    private EntityManagerInterface $entityManager;
    private SettingsMainIdentificator $id;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->id = new SettingsMainIdentificator();
    }
    
    public function get() : ?EntitySettingsMain\Event\SettingsMainEvent
    {
        $qb = $this->entityManager->createQueryBuilder();
    
        $qb->select('event');
        $qb->from(EntitySettingsMain\SettingsMain::class, 'settings');
        $qb->join(EntitySettingsMain\Event\SettingsMainEvent::class, 'event', 'WITH', 'event.id = settings.event');
		
        $qb->where('settings.id = :settings');
        $qb->setParameter(':settings', $this->id, SettingsMainIdentificator::TYPE);
        
        return $qb->getQuery()->getOneOrNullResult();
        
    }
    
}