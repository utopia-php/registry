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

    public function setUp()
    {
        $this->registry = new Registry();
    }

    public function tearDown()
    {
        $this->registry = null;
    }

    public function testTest()
    {
        $this->registry->set('array', function () {
            return new ArrayObject(['test']);
        });

        $this->assertEquals(0, $this->registry->created['array']);

        $this->assertCount(1, $this->registry->get('array'));

        $this->registry->get('array')[] = 'Hello World';

        $this->assertCount(2, $this->registry->get('array'));

        $this->assertEquals(3, $this->registry->counter['array']);

        $this->assertEquals(1, $this->registry->created['array']);

        // Fresh Copy
        $this->assertCount(1, $this->registry->get('array', true));
    }
}