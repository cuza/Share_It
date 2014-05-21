<?php
/*
 * This file is part of the Sonata project.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Sonata\GoutteBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * GoutteExtension
 *
 *
 * @author     Thomas Rabaix <thomas.rabaix@sonata-project.org>
 */
class SonataGoutteExtension extends Extension
{

    /**
     * Loads the url shortener configuration.
     *
     * @param array            $config    An array of configuration settings
     * @param ContainerBuilder $container A ContainerBuilder instance
     */
    public function load(array $config, ContainerBuilder $container)
    {

        $config = call_user_func_array('array_merge_recursive', $config);
        
        // define the page manager
        $definition = new Definition($config['class']);
        foreach ($config['clients'] as $name => $configuration) {

            $configuration['config'] = isset($configuration['config']) ? $configuration['config'] : array();
            $configuration['server'] = isset($configuration['server']) ? $configuration['server'] : array();

            $definition->addMethodCall('addClientConfiguration', array($name, $configuration));
        }

        $container->setDefinition('goutte', $definition);

    }

    /**
     * Returns the base path for the XSD files.
     *
     * @return string The XSD base path
     */
    public function getXsdValidationBasePath()
    {

        return __DIR__.'/../Resources/config/schema';
    }

    public function getNamespace()
    {

        return 'http://www.sonata-project.org/schema/dic/goutte';
    }

    public function getAlias()
    {

        return "sonata_goutte";
    }
}
