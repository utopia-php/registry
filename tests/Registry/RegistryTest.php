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
    /**
     * @var Registry
     */
    protected $registry = null;

    public function setUp(): void
    {
        $this->registry = new Registry();
    }

    public function tearDown(): void
    {
        $this->registry = null;
    }

    public function testTest()
    {
        $this->registry->set('array', function () {
            return new ArrayObject(['test']);
        });

        $this->assertCount(1, $this->registry->get('array'));

        $this->registry->get('array')[] = 'Hello World';

        $this->assertCount(2, $this->registry->get('array'));

        // Fresh Copy
        $this->assertCount(1, $this->registry->get('array', true));

        $this->registry->set('time', function () {
            return microtime();
        });

        // Test for different contexts

        $timeX = $this->registry->get('time');
        $timeY = $this->registry->get('time');

        $this->assertEquals($timeX, $timeY);
        
        // Test for cached instance

        $timeY = $this->registry->get('time');

        $this->assertEquals($timeX, $timeY);

        // Switch Context

        $this->registry->context('new');

        $timeY = $this->registry->get('time');

        $this->assertNotEquals($timeX, $timeY);

        // Test for cached instance

        $timeY = $this->registry->get('time');

        $this->assertNotEquals($timeX, $timeY);


        // Test fresh copies

        $this->registry->set('fresh', function () {
            return microtime();
        }, true);

        $copy1 = $this->registry->get('fresh');
        $copy2 = $this->registry->get('fresh');
        $copy3 = $this->registry->get('fresh');

        $this->assertNotEquals($copy1, $copy2);
        $this->assertNotEquals($copy2, $copy3);
        $this->assertNotEquals($copy1, $copy3);
    }
}