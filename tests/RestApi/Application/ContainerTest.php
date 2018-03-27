<?php
declare(strict_types=1);

namespace RestApi\Tests\Application;

use PHPUnit\Framework\TestCase;
use RestApi\Application\Container;

class ContainerTest extends TestCase
{

    public function testParametersCanBeChanged(): void
    {
        $container = new Container();
        $this->assertFalse($container->has('a'));

        $container->addDefinitions(['a' => 5]);
        $this->assertTrue($container->has('a'));
        $this->assertEquals(5, $container->get('a'));
    }

    /**
     * @expectedException \RestApi\Exception\ContainerNotFoundException
     */
    public function testContainerThrowsExceptionForUnknownDependencies(): void
    {
        $container = new Container();
        $container->get('a');
    }

    public function testContainerResolveClasses(): void
    {
        $container = new Container();
        $obj = $container->get(Container::class);
        $this->assertInstanceOf(Container::class, $obj);
    }

}
