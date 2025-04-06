<?php

declare(strict_types=1);

namespace App\Http\Services\Handlers;

interface HandlerInterface
{
    public function setNext(HandlerInterface $handler): HandlerInterface;
    public function handle(array $context): array;
}
