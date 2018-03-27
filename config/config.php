<?php
declare(strict_types=1);

return [
    'apiKey' => getenv('MESSAGEBIRD_API_KEY'),
    'logsPath' => __DIR__ . '/../storage/logs/app.log'
];
