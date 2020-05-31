<?php

declare(strict_types=1);

namespace App\Controller;

use DomainException;
use Psr\Log\LoggerInterface;

class ErrorHandler
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function handle(DomainException $e): void
    {
        $this->logger->error($e->getMessage(), ['exception' => $e]);
    }
}
