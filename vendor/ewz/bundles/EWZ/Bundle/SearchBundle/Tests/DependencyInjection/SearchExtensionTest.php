<?php

namespace EWZ\Bundle\SearchBundle\Tests\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

use EWZ\Bundle\SearchBundle\DependencyInjection\EWZSearchExtension;

class SearchExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers EWZ\Bundle\SearchBundle\DependencyInjection\EWZSearchExtension::load
     */
    public function testConfigLoad()
    {
        $container = $this->getContainer();
        $loader = new EWZSearchExtension();

        $loader->load(array(), $container);
        $this->assertEquals('Bundle\\SearchBundle\\Lucene\\LuceneSearch', $container->getParameter('ewz_search.lucene.search.class'), '->luceneLoad() loads the lucene.xml file if not already loaded');
    }
}
