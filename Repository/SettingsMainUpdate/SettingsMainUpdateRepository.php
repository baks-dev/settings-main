<?php

namespace BaksDev\Settings\Main\Repository\SettingsMainUpdate;


use BaksDev\Core\Doctrine\ORMQueryBuilder;
use BaksDev\Settings\Main\Entity\Event\SettingsMainEvent;
use BaksDev\Settings\Main\Entity\SettingsMain;
use BaksDev\Settings\Main\Type\Id\SettingsMainIdentificator;

final class SettingsMainUpdateRepository implements SettingsMainUpdateInterface
{

    //private EntityManagerInterface $entityManager;

    private SettingsMainIdentificator $id;
    private ORMQueryBuilder $ORMQueryBuilder;


    public function __construct(ORMQueryBuilder $ORMQueryBuilder)
    {
        $this->id = new SettingsMainIdentificator();
        $this->ORMQueryBuilder = $ORMQueryBuilder;
    }


    public function get(): ?SettingsMainEvent
    {
        $qb = $this->ORMQueryBuilder->createQueryBuilder(self::class);

        $qb
            ->from(SettingsMain::class, 'settings')
            ->where('settings.id = :settings')
            ->setParameter(':settings', $this->id, SettingsMainIdentificator::TYPE);

        $qb
            ->select('event')
            ->join(
                SettingsMainEvent::class,
                'event',
                'WITH',
                'event.id = settings.event'
            );


        return $qb->getOneOrNullResult();

    }

}