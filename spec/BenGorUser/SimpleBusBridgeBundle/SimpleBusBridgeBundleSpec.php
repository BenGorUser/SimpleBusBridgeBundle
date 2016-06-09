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

namespace spec\BenGorUser\SimpleBusBridgeBundle;

use BenGorUser\SimpleBusBridgeBundle\DependencyInjection\Compiler\SimpleBusPass;
use BenGorUser\SimpleBusBridgeBundle\SimpleBusBridgeBundle;
use BenGorUser\UserBundle\LoadableBundle;
use PhpSpec\ObjectBehavior;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Spec file of SimpleBusBridgeBundle class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class SimpleBusBridgeBundleSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(SimpleBusBridgeBundle::class);
    }

    function it_extends_symfony_bundle()
    {
        $this->shouldHaveType(Bundle::class);
    }

    function it_implements_loadable_bundle()
    {
        $this->shouldImplement(LoadableBundle::class);
    }

    function it_builds_without_dependendent_bundles_enabled(ContainerBuilder $container)
    {
        $this->shouldThrow(RuntimeException::class)->duringBuild($container);
    }

    function it_builds(ContainerBuilder $container)
    {
        $container->getParameter('kernel.bundles')->shouldBeCalled()->willReturn([
            'BenGorUserBundle' => 'BenGorUser\\UserBundle\\BenGorUserBundle',
            'DoctrineBundle'   => 'Doctrine\\Bundle\\DoctrineBundle\\DoctrineBundle',
        ]);

        $this->build($container);
    }

    function it_loads(ContainerBuilder $container)
    {
        $container->addCompilerPass(
            new SimpleBusPass(), PassConfig::TYPE_OPTIMIZE
        )->shouldBeCalled()->willReturn($container);

        $this->load($container);
    }
}
