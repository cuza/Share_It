<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license. For more information, see
 * <http://www.doctrine-project.org>.
 */

namespace Doctrine\Tests\Common\DataFixtures;

require_once __DIR__ . '/TestInit.php';

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use PHPUnit_Framework_TestCase;

/**
 * Base test class
 *
 * @author Jonathan H. Wage <jonwage@gmail.com>
 */
abstract class BaseTest extends PHPUnit_Framework_TestCase
{
    protected function getMockEntityManager()
    {
        $driver = $this->getMock('Doctrine\DBAL\Driver');
        $driver->expects($this->once())
            ->method('getDatabasePlatform')
            ->will($this->returnValue($this->getMock('Doctrine\DBAL\Platforms\MySqlPlatform')));

        $conn = $this->getMock('Doctrine\DBAL\Connection', array(), array(array(), $driver));
        $conn->expects($this->once())
            ->method('getEventManager')
            ->will($this->returnValue($this->getMock('Doctrine\Common\EventManager')));

        $config = $this->getMock('Doctrine\ORM\Configuration');
        $config->expects($this->once())
            ->method('getProxyDir')
            ->will($this->returnValue('test'));

        $config->expects($this->once())
            ->method('getProxyNamespace')
            ->will($this->returnValue('Proxies'));

        $config->expects($this->once())
            ->method('getMetadataDriverImpl')
            ->will($this->returnValue($this->getMock('Doctrine\ORM\Mapping\Driver\DriverChain')));

        $em = EntityManager::create($conn, $config);
        return $em;
    }

    /**
     * EntityManager mock object together with
     * annotation mapping driver
     *
     * @return EntityManager
     */
    protected function getMockAnnotationReaderEntityManager()
    {
        $dbParams = array('driver' => 'pdo_sqlite', 'memory' => true);
        $config = Setup::createAnnotationMetadataConfiguration(array(__DIR__.'/TestEntity'), true);
        return EntityManager::create($dbParams, $config);
    }

    /**
     * EntityManager mock object together with
     * annotation mapping driver and pdo_sqlite
     * database in memory
     *
     * @return EntityManager
     */
    protected function getMockSqliteEntityManager()
    {
        $dbParams = array('driver' => 'pdo_sqlite', 'memory' => true);
        $config = Setup::createAnnotationMetadataConfiguration(array(__DIR__.'/TestEntity'), true);
        return EntityManager::create($dbParams, $config);
    }
}
