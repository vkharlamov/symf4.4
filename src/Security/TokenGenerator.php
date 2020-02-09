<?php

namespace App\Security;

use Psr\Log\LoggerInterface;

/**
 * Class TokenGenerator
 * @package App\Security
 */
class TokenGenerator
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return string
     *
     * @throws \Exception
     */
    public function getRegistrationToken(int $len = 30): string
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
