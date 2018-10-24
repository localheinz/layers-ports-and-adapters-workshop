<?php

declare(strict_types=1);

namespace MeetupOrganizing\Domain\Entity;

use Assert\Assertion;

final class Name
{
    /**
     * @var string
     */
    private $text;

    public function __toString(): string
    {
        return $this->text;
    }

    public static function fromString($text): self
    {
        Assertion::notEmpty($text);

        $name = new self();

        $name->text = $text;

        return $name;
    }
}
