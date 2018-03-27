<?php
declare(strict_types=1);

namespace RestApi\Http\Handler;

use RestApi\Http\JsonResponse;
use RestApi\MessageRequestSet;
use RestApi\MessageRequest;
use RestApi\Http\Request;

/**
 * SMS API handler class.
 * Can be used by passing initialized request object containing JSON request data in it. Sample:
 * {"recipient": 3197004499527, "originator": "MessageBird", "message": "test message."}
 *
 * Class SendSmsHandler
 * @package RestApi\Http\Handler
 */
final class SendSmsHandler
{

    /**
     * @var MessageRequestSet
     */
    private $messageSet;

    /**
     * SendSmsHandler constructor.
     * @param MessageRequestSet $messageSet
     */
    public function __construct(MessageRequestSet $messageSet)
    {
        $this->messageSet = $messageSet;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     *
     * @throws \RuntimeException if impossible to add message to set
     */
    public function __invoke(Request $request)
    {
        try {
            $message = new MessageRequest(
                (string)$request->input('originator'),
                (string)$request->input('recipient'),
                (string)$request->input('message')
            );
        } catch (\InvalidArgumentException $ex) {
            return new JsonResponse(
                [ 'error' => $ex->getMessage() ],
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $this->messageSet->add($message);

        return new JsonResponse([
            'status' => 'ok'
        ]);
    }

}
