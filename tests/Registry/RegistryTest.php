<?php
/**
 * Utopia PHP Framework
 *
 * @package Registry
 * @subpackage Tests
 *
 * @link https://github.com/utopia-php/framework
 * @author Eldad Fux <eldad@appwrite.io>
 * @version 1.0 RC4
 * @license The MIT License (MIT) <http://www.opensource.org/licenses/mit-license.php>
 */

namespace Utopia\Tests;

use ArrayObject;
use Utopia\Registry\Registry;
use PHPUnit\Framework\TestCase;

class RegistryTest extends TestCase
{
    protected ?Registry $registry = null;

    public function setUp(): void
    {
        $this->registry = new Registry();
    }

    public function tearDown(): void
    {
        $this->registry = null;
    }

    /**
     * @throws \Exception
     */
    public function testGet() {
        $this->registry->set('array', function () {
            return new ArrayObject(['test']);
        });
        $this->registry->get('array')[] = 'Hello World';

        $this->assertCount(2, $this->registry->get('array'));
    }

    /**
     * @throws \Exception
     */
    public function testSet() {
        $this->registry->set('array', function () {
            return new ArrayObject(['test']);
        });
        $this->assertCount(1, $this->registry->get('array'));
    }

    /**
     * @throws \Exception
     */
    public function testHas()
    {
        $this->registry->set('item', function () {
            return ['test'];
        });
        $this->assertTrue($this->registry->has('item'));

        $this->registry->set('item', static fn() => null);

        $this->assertFalse($this->registry->has('item'));
    }

    /**
     * @throws \Exception
     */
    public function testGetFresh() {
        $this->registry->set('array', function () {
            return new ArrayObject(['test']);
        });
        $this->assertCount(1, $this->registry->get('array', true));
    }

    /**
     * @throws \Exception
     */
    public function testSetFresh() {
        $this->registry->set('fresh', function () {
            return microtime();
        }, true);

        // Added usleep because some runs were so fast that the microtime was the same
        $copy1 = $this->registry->get('fresh');
        usleep(1);
        $copy2 = $this->registry->get('fresh');
        usleep(1);
        $copy3 = $this->registry->get('fresh');

        $this->assertNotEquals($copy1, $copy2);
        $this->assertNotEquals($copy2, $copy3);
        $this->assertNotEquals($copy1, $copy3);
    }

    /**
     * @throws \Exception
     */
    public function testGetCaching()
    {
        $this->registry->set('time', function () {
            return microtime();
        });

        $timeX = $this->registry->get('time');
        $timeY = $this->registry->get('time');

        $this->assertEquals($timeX, $timeY);
    }

    /**
     * @throws \Exception
     */
    public function testContextSwitching()
    {
        $this->registry->set('time', function () {
            return microtime();
        });

        $timeX = $this->registry->get('time');
        $timeY = $this->registry->get('time');

        $this->registry->context('new');

        $timeY = $this->registry->get('time');

        $this->assertNotEquals($timeX, $timeY);
    }
}