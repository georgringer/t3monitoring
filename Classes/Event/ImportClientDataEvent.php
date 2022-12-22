<?php

declare(strict_types=1);

namespace T3Monitor\T3monitoring\Event;

final class ImportClientDataEvent
{
    private array $json;
    private array $row;
    private array $update;

    public function __construct(array $json, array $row, array $update)
    {
        $this->json = $json;
        $this->row = $row;
        $this->update = $update;
    }

    public function getJson(): array
    {
        return $this->json;
    }

    public function getRow(): array
    {
        return $this->row;
    }

    public function getUpdate(): array
    {
        return $this->update;
    }

    public function setUpdate(array $update): void
    {
        $this->update = $update;
    }
}
