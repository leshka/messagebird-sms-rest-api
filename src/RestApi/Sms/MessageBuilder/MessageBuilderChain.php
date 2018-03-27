<?php
declare(strict_types=1);

namespace RestApi\Sms\MessageBuilder;

use RestApi\MessageRequest;

/**
 * Message builder chain class
 * Accepts possible builders as constructor argument.
 *
 * Class MessageBuilderChain
 * @package RestApi\Sms\MessageBuilder
 */
final class MessageBuilderChain
{

    /**
     * @var MessageBuilder
     */
    private $firstBuilder;

    /**
     * MessageBuilderChain constructor.
     * @param MessageBuilder[] $builders
     *
     * @throws \InvalidArgumentException in case the builder data is empty or contains invalid classes
     */
    public function __construct(array $builders)
    {
        $this->validateBuilders($builders);

        $current = null;
        $first = null;
        foreach ($builders as $builder) {
            if (null === $first) {
                $first = $builder;
            }

            if (null !== $current) {
                /**
                 * @var MessageBuilder $current
                 */
                $current->setNext($builder);
            }
            $current = $builder;
        }
        $this->firstBuilder = $first;
    }

    /**
     * @param array $builders
     *
     * @throws \InvalidArgumentException
     */
    private function validateBuilders(array $builders): void
    {
        if (!\count($builders)) {
            throw new \InvalidArgumentException('There should be at least one builder');
        }
        foreach ($builders as $builder) {
            if (!is_subclass_of($builder, MessageBuilder::class)) {
                throw new \InvalidArgumentException('Builders should be extended from MessageBuilder');
            }
        }
    }

    /**
     * @param MessageRequest $message
     * @return array|\MessageBird\Objects\Message[]
     */
    public function build(MessageRequest $message): array
    {
        return $this->firstBuilder->build($message);
    }

}
