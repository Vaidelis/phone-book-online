<?php

declare(strict_types=1);

namespace App\Http\Services\Handlers;

abstract class Handler implements HandlerInterface
{
    private ?HandlerInterface $nextHandler = null;

    public function setNext(HandlerInterface $handler): HandlerInterface
    {
        $this->nextHandler = $handler;
        return $handler;
    }

    public function handle(array $context): array
    {
        if ($this->nextHandler) {
            return $this->nextHandler->handle($context);
        }

        return $context;
    }
}

