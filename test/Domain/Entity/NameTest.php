<?php

declare(strict_types=1);

namespace MeetupOrganizing\Test\Domain\Entity;

use MeetupOrganizing\Domain\Entity\Name;

/**
 * @internal
 */
final class NameTest extends \PHPUnit\Framework\TestCase
{
    public function testItWrapsAString(): void
    {
        $nameText = 'Non-empty string';
        $name = Name::fromString($nameText);
        $this->assertEquals($nameText, (string) $name);
    }

    public function testItShouldBeANonEmptyString(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Name::fromString('');
    }
}
