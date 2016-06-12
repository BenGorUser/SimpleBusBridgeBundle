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
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Simple bus and Doctrine ODM MongoDB bridge bundle extension class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class SimpleBusDoctrineODMMongoDBBridgeExtension extends Extension implements PrependExtensionInterface, SimpleBusTaggerExtension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $loader->load('doctrine_odm_mongodb.yml');
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
     * {@inheritdoc}
     */
    public function addMiddlewareTags(ContainerBuilder $container, $user)
    {
        $container->getDefinition(
            'bengor_user.simple_bus_bridge_bundle.doctrine_odm_mongodb_transactional_middleware'
        )->addTag(
            'bengor_user_' . $user . '_command_bus_middleware', ['priority' => '0']
        );

        return $container;
    }
}
