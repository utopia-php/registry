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
    }
}