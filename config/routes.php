<?php
declare(strict_types=1);

return [
    [ 'POST', '/api/sms', RestApi\Http\Handler\SendSmsHandler::class ]
];
