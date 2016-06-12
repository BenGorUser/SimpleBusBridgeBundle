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

namespace spec\BenGorUser\SimpleBusBridgeBundle\EventListener;

use BenGorUser\SimpleBusBridgeBundle\EventListener\CollectsEventsFromDocuments;
use BenGorUser\User\Domain\Model\Event\UserRegistered;
use BenGorUser\User\Domain\Model\User;
use BenGorUser\User\Domain\Model\UserAggregateRoot;
use BenGorUser\User\Domain\Model\UserEmail;
use BenGorUser\User\Domain\Model\UserId;
use BenGorUser\User\Domain\Model\UserToken;
use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\Events;
use PhpSpec\ObjectBehavior;
use SimpleBus\Message\Recorder\ContainsRecordedMessages;

/**
 * Spec file of CollectsEventsFromDocuments class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class CollectsEventsFromDocumentsSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(CollectsEventsFromDocuments::class);
    }

    function it_implements_doctrine_event_subscriber()
    {
        $this->shouldImplement(EventSubscriber::class);
    }

    function it_implements_contain_recorded_messages()
    {
        $this->shouldImplement(ContainsRecordedMessages::class);
    }

    function it_gets_subscribed_events()
    {
        $this->getSubscribedEvents()->shouldReturn([
            Events::postPersist,
            Events::postUpdate,
            Events::postRemove,
        ]);
    }

    function it_post_persist(LifecycleEventArgs $event, UserAggregateRoot $user)
    {
        $event->getEntity()->shouldBeCalled()->willReturn($user);
        $user->events()->shouldBeCalled()->willReturn([]);

        $user->eraseEvents()->shouldBeCalled();

        $this->postPersist($event);
    }

    function it_post_update(LifecycleEventArgs $event, UserAggregateRoot $user)
    {
        $event->getEntity()->shouldBeCalled()->willReturn($user);
        $user->events()->shouldBeCalled()->willReturn([]);

        $user->eraseEvents()->shouldBeCalled();

        $this->postUpdate($event);
    }

    function it_post_remove(LifecycleEventArgs $event, UserAggregateRoot $user)
    {
        $event->getEntity()->shouldBeCalled()->willReturn($user);
        $user->events()->shouldBeCalled()->willReturn([]);

        $user->eraseEvents()->shouldBeCalled();

        $this->postRemove($event);
    }

    function it_manage_recorded_events(LifecycleEventArgs $event, UserAggregateRoot $user)
    {
        $event->getEntity()->shouldBeCalled()->willReturn($user);
        $user->events()->shouldBeCalled()->willReturn([
            new UserRegistered(
                new UserId('user-id'),
                new UserEmail('bengor@user.com'),
                new UserToken('confirmation-token')
            ),
        ]);
        $user->eraseEvents()->shouldBeCalled();

        $this->postPersist($event);

        $this->recordedMessages()->shouldBeArray();
        $this->recordedMessages()->shouldHaveCount(1);
        $this->eraseMessages();
        $this->recordedMessages()->shouldHaveCount(0);
    }
}
