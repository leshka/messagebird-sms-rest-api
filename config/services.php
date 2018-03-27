<?php
declare(strict_types=1);

use Psr\Log\LoggerInterface;
use RestApi\Application\Container;
use RestApi\Logger\Handler\FileHandler;
use RestApi\MessageRequestSet;
use RestApi\Sms\Client\LimitedThroughputClient;
use RestApi\Sms\Client\RemoteClient;
use RestApi\Sms\MessageBuilder\ConcatenatedMessageBuilder;
use RestApi\Sms\MessageBuilder\MessageBuilderChain;
use RestApi\Sms\MessageBuilder\ShortMessageBuilder;

return [

    MessageRequestSet::class => function() {
        return new MessageRequestSet(
            __DIR__ . '/../storage/messages'
        );
    },

    MessageBuilderChain::class => function(Container $c) {
        return new MessageBuilderChain([
            $c->get(ShortMessageBuilder::class),
            $c->get(ConcatenatedMessageBuilder::class)
        ]);
    },

    RemoteClient::class => function(Container $c) {
        return new RemoteClient(
            new \MessageBird\Client(
                $c->get('apiKey')
            ),
            $c->get(LoggerInterface::class)
        );
    },

    LoggerInterface::class => function(Container $c) {
        return new RestApi\Logger\Logger([
            new FileHandler((string)$c->get('logsPath')),
            new FileHandler('php://stdout')
        ]);
    },

    LimitedThroughputClient::class => function(Container $c) {
        return new LimitedThroughputClient(
            $c->get(RemoteClient::class)
        );
    }

];
