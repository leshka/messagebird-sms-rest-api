<?php
declare(strict_types=1);

namespace RestApi\Http;

/**
 * HTTP response.
 * Should be initialized with it's string content and used with "send" method
 *
 * Class Response
 * @package RestApi\Http
 */
class Response implements ResponseInterface
{

    /**
     * @var string
     */
    protected $httpVersion = '1.0';

    /**
     * @var string
     */
    protected $content;

    /**
     * @var int
     */
    protected $status;

    /**
     * Response constructor.
     * @param string $content output
     * @param int $status
     */
    public function __construct(string $content = '', int $status = 200)
    {
        $this->content = $content;
        $this->status = $status;
    }

    /**
     *  Sending headers and content to browser
     */
    public function send(): void
    {
        header(sprintf('HTTP/%s %s', self::PROTOCOL_VERSION, $this->status));
        echo $this->content;
    }

}
