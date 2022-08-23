<?php

declare(strict_types=1);

namespace App\Model;

/**
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
final class Workflow
{
    public function __construct(private readonly string $id, private readonly string $name, private readonly iterable $runs)
    {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Run[]
     */
    public function getRuns(): iterable
    {
        return $this->runs;
    }
}
