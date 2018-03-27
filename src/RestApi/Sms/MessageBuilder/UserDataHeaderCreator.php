<?php
declare(strict_types=1);

namespace RestApi\Sms\MessageBuilder;

use InvalidArgumentException;

/**
 * Generates user data headers
 *
 * Class UserDataHeaderCreator
 * @package RestApi\Sms\MessageBuilder
 */
final class UserDataHeaderCreator
{

    private const UDH_LENGTH = 5;

    private const ELEMENT_IDENTIFIER = 0;

    private const HEADER_LENGTH = 3;

    /**
     * @param int $value
     * @param string $label
     *
     * @throws InvalidArgumentException
     */
    private function validateNumber(int $value, string $label): void
    {
        $filterResult = filter_var(
            $value,
            FILTER_VALIDATE_INT,
            [
                'options' => [
                    'min_range' => 1,
                    'max_range' => 255
                ]
            ]
        );

        if ($filterResult === false) {
            throw new InvalidArgumentException(
                sprintf('%s can be a number in interval [1, 255]', $label)
            );
        }
    }

    /**
     * Creates UDH by the given parameters.
     * Input numbers should be in range [1, 255]
     *
     * @param int $partitionNumber
     * @param int $totalPartitions
     * @param int $reference
     * @return string
     *
     * @throws \InvalidArgumentException in case the input arguments are invalid
     */
    public function create(int $partitionNumber, int $totalPartitions, int $reference): string
    {
        $this->validateNumber($partitionNumber, 'Partition number');
        $this->validateNumber($totalPartitions, 'Total partitions');
        $this->validateNumber($reference, 'Reference');

        return sprintf(
            '%02X%02X%02X%02X%02X%02X',
            self::UDH_LENGTH,
            self::ELEMENT_IDENTIFIER,
            self::HEADER_LENGTH,
            $reference,
            $totalPartitions,
            $partitionNumber
        );
    }

}
