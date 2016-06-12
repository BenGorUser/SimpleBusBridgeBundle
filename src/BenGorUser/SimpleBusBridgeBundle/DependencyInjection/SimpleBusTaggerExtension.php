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

use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Interface to add Symfony service tags to the
 * Simple Bus middleware services.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
interface SimpleBusTaggerExtension
{
    /**
     * Adds tags to Simple bus middleware.
     *
     * @param ContainerBuilder $container The container
     * @param string           $user      The user name
     *
     * @return ContainerBuilder
     */
    public function addMiddlewareTags(ContainerBuilder $container, $user);
}
