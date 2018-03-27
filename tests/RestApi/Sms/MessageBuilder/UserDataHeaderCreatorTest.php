<?php
declare(strict_types=1);

namespace RestApi\Tests\Sms\MessageBuilder;

use PHPUnit\Framework\TestCase;
use RestApi\Sms\MessageBuilder\UserDataHeaderCreator;

class UserDataHeaderCreatorTest extends TestCase
{

    public function testHasValidDataHeader(): void
    {
        $creator = new UserDataHeaderCreator();
        $header = $creator->create(1, 2, 5);
        $this->assertEquals(12, \strlen($header));
        $this->assertEquals('05', substr($header, 6, 2));
        $this->assertEquals('02', substr($header, 8, 2));
        $this->assertEquals('01', substr($header, 10, 2));

    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidPartitionNumber(): void
    {
        $creator = new UserDataHeaderCreator();
        $creator->create(-1, 1, 1);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidTotalPartitions(): void
    {
        $creator = new UserDataHeaderCreator();
        $creator->create(1, 300, 1);

    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidReference(): void
    {
        $creator = new UserDataHeaderCreator();
        $creator->create(1, 1, -5);
    }

}
