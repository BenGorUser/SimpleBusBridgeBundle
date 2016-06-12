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

namespace BenGorUser\SimpleBusBridgeBundle;

use BenGorUser\SimpleBusBridgeBundle\DependencyInjection\Compiler\SimpleBusPass;
use BenGorUser\UserBundle\DependentBenGorUserBundle;
use BenGorUser\UserBundle\LoadableBundle;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Simple bus and Doctrine ODM MongoDB bridge bundle kernel class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class SimpleBusDoctrineODMMongoDBMBridgeBundle extends Bundle
{
    use DependentBenGorUserBundle;

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $this->checkDependencies([
            'BenGorUserBundle','DoctrineODMMongoDBBridgeBundle', 'DoctrineMongoDBBundle',
        ], $container);
    }
}
