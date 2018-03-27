<?php
declare(strict_types=1);

namespace RestApi\Http;

/**
 * HTTP response.
 * Should be initialized with it's array content (which will be transformed to json) and used with "send" method
 *
 * Class JsonResponse
 * @package RestApi\Http
 */
class JsonResponse implements ResponseInterface
{

    /**
     * @var array
     */
    private $content;
    /**
     * @var int
     */
    private $status;

    /**
     * JsonResponse constructor.
     * @param array $content
     * @param int $status
     */
    public function __construct(array $content = [], int $status = self::HTTP_OK)
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
        echo json_encode($this->content);
    }
}
