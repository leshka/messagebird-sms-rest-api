<?php
declare(strict_types=1);

namespace RestApi\Tests;

use PHPUnit\Framework\TestCase;
use RestApi\ParameterBag;

class ParameterBagTest extends TestCase
{

    public function testBasicOperations(): void
    {
        # case with initialization
        $bag = new ParameterBag([ 'a' => 5 ]);
        $this->assertTrue($bag->has('a'));
        $this->assertFalse($bag->has('b'));
        $this->assertEquals(5, $bag->get('a'));

        # trying to add to bag
        $bag->set('b', 'test');
        $this->assertTrue($bag->has('a'));
        $this->assertTrue($bag->has('b'));
        $this->assertFalse($bag->has('c'));
        $this->assertEquals('test', $bag->get('b'));
    }

}
