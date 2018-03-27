<?php
declare(strict_types=1);

namespace RestApi\Tests\Http;

use PHPUnit\Framework\TestCase;
use RestApi\Http\Request;

class RequestTest extends TestCase
{

    public function testDetectsMethod(): void
    {
        $request = new Request([]);
        $this->assertEquals('GET', $request->getMethod()); // default method

        $request = new Request(['REQUEST_METHOD' => 'POST']);
        $this->assertEquals('POST', $request->getMethod());
    }

    public function testDetectsRequestUri(): void
    {
        $request = new Request([]);
        $this->assertEquals('/', $request->getRequestUri()); // default uri

        $request = new Request(['REQUEST_URI' => '/test']);
        $this->assertEquals('/test', $request->getRequestUri()); // default uri
    }

    public function testParsesJson(): void
    {
        $request = new Request(
            [],
            json_encode(['a' => 5, 'b' => 6])
        );
        $this->assertEquals(5, $request->input('a'));
        $this->assertEquals(6, $request->input('b'));
        $this->assertNull($request->input('c'));
    }

}
