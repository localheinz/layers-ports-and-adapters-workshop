<?php

declare(strict_types=1);

namespace MeetupOrganizing\Domain\Entity;

use Assert\Assertion;

final class Description
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

        $description = new self();

        $description->text = $text;

        return $description;
    }
}
