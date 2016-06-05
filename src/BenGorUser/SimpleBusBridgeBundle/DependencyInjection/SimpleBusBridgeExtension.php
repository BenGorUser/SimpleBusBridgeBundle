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

namespace BenGorUser\SimpleBusBridgeBundle\DependencyInjection;

use BenGorUser\UserBundle\DependencyInjection\Configuration;
use SimpleBus\Message\Handler\DelegatesToMessageHandlerMiddleware;
use SimpleBus\Message\Recorder\HandlesRecordedMessagesMiddleware;
use SimpleBus\Message\Subscriber\NotifiesMessageSubscribersMiddleware;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Simple bus bridge bundle extension class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class SimpleBusBridgeExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $loader->load('middlewares.yml');
        $loader->load('event_recorders.yml');
    }

    /**
     * {@inheritdoc}
     */
    public function prepend(ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $configs = $container->getExtensionConfig('ben_gor_user');
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('bengor_user.config', $config);
    }

    /**
     * Adds tags to Simple bus middlewares.
     *
     * @param ContainerBuilder $container The container
     * @param string           $user      The user name
     *
     * @return ContainerBuilder
     */
    public static function addMiddlewareTags(ContainerBuilder $container, $user)
    {
        // Related with Command Bus
        $container->setDefinition(
            'bengor_user.simple_bus.' . $user . '_command_bus.delegates_to_message_handler_middleware',
            (new Definition(DelegatesToMessageHandlerMiddleware::class))->addTag(
                'bengor_user_' . $user . '_command_bus_middleware', ['priority' => '-1000']
            )
        );
        $container->getDefinition(
            'bengor_user.simple_bus.finishes_command_before_handling_next_middleware'
        )->addTag(
            'bengor_user_' . $user . '_command_bus_middleware', ['priority' => '1000']
        );
        $container->getDefinition(
            'bengor_user.simple_bus.doctrine_transactional_middleware'
        )->addTag(
            'bengor_user_' . $user . '_command_bus_middleware', ['priority' => '0']
        );

        // Related with Event Bus
        $container->setDefinition(
            'bengor_user.simple_bus.' . $user . '_event_bus.delegates_to_message_handler_middleware',
            (new Definition(NotifiesMessageSubscribersMiddleware::class))->addTag(
                'bengor_user_' . $user . '_event_bus_middleware', ['priority' => '-1000']
            )
        )->addTag(
            'bengor_user_' . $user . '_command_bus_middleware', ['priority' => '200']
        );
        $container->setDefinition(
            'bengor_user.simple_bus.' . $user . '_event_bus.handles_recorded_messages_middleware',
            (new Definition(HandlesRecordedMessagesMiddleware::class))->addTag(
                'bengor_user_' . $user . '_event_bus_middleware', ['priority' => '-1000']
            )
        )->addTag(
            'bengor_user_' . $user . '_command_bus_middleware', ['priority' => '200']
        );

        return $container;
    }
}
