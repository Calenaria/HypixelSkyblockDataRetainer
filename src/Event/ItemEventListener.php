<?php

namespace App\Event;

use App\Entity\SkyblockItem;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: SkyblockItem::class)]
class ItemEventListener {
    public function getSubscribedEvents()
    {
        return [
            Events::postPersist,
            Events::postUpdate,
        ];
    }

    public function postUpdate()
    {
        $this->processItem("");
    }

    public function postPersist(SkyblockItem $item, PostPersistEventArgs $args)
    {
    }

    public function processItem(mixed $item) {

    }
}