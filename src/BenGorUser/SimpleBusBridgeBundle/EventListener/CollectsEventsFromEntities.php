<?php

/*
 * This file is part of the BenGorUser package.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BenGorUser\SimpleBusBridgeBundle\EventListener;

use BenGorUser\User\Domain\Model\UserAggregateRoot;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use SimpleBus\Message\Recorder\ContainsRecordedMessages;

/**
 * Event listener that joins Doctrine transaction with domain events.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class CollectsEventsFromEntities implements EventSubscriber, ContainsRecordedMessages
{
    /**
     * Domain events collection.
     *
     * @var array
     */
    private $collectedEvents = [];

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return [
            Events::postPersist,
            Events::postUpdate,
            Events::postRemove,
        ];
    }

    /**
     * Callback for "postPersist" Doctrine event.
     *
     * @param LifecycleEventArgs $event Doctrine event
     */
    public function postPersist(LifecycleEventArgs $event)
    {
        $this->collectEventsFromEntity($event);
    }

    /**
     * Callback for "postUpdate" Doctrine event.
     *
     * @param LifecycleEventArgs $event Doctrine event
     */
    public function postUpdate(LifecycleEventArgs $event)
    {
        $this->collectEventsFromEntity($event);
    }

    /**
     * Callback for "postRemove" Doctrine event.
     *
     * @param LifecycleEventArgs $event Doctrine event
     */
    public function postRemove(LifecycleEventArgs $event)
    {
        $this->collectEventsFromEntity($event);
    }

    /**
     * Gets the domain events collection.
     *
     * @return array
     */
    public function recordedMessages()
    {
        return $this->collectedEvents;
    }

    /**
     * Clears the domain events collection.
     */
    public function eraseMessages()
    {
        $this->collectedEvents = [];
    }

    /**
     * Gets the domain events from the aggregate root and loads
     * inside collected events collection, removing from the entity.
     *
     * @param LifecycleEventArgs $event The Doctrine event
     */
    private function collectEventsFromEntity(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();

        if ($entity instanceof UserAggregateRoot) {
            foreach ($entity->events() as $event) {
                $this->collectedEvents[] = $event;
            }

            $entity->eraseEvents();
        }
    }
}
