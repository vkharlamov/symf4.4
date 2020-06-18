<?php

namespace App\Security;

use Psr\Log\LoggerInterface;

/**
 * Class TokenGenerator
 * @package App\Security
 */
class TokenGenerator
{
    private const MIN_LENGTH = 30;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param int $len
     *
     * @return string
     *
     */
    public function getRegistrationToken(int $len = self::MIN_LENGTH): string
    {
        $secret = '';

        try {
            $secret = bin2hex(random_bytes($len));
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage() . ' ' . $e->getTraceAsString());
        }

        return $secret;
    }
}
