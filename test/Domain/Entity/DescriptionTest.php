<?php

declare(strict_types=1);

namespace MeetupOrganizing\Test\Domain\Entity;

use MeetupOrganizing\Domain\Entity\Description;

/**
 * @internal
 */
final class DescriptionTest extends \PHPUnit\Framework\TestCase
{
    public function testItWrapsAString(): void
    {
        $descriptionText = 'Non-empty string';
        $description = Description::fromString($descriptionText);
        $this->assertEquals($descriptionText, (string) $description);
    }

    public function testItShouldBeANonEmptyString(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Description::fromString('');
    }
}
