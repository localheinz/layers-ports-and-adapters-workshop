<?php

declare(strict_types=1);

namespace MeetupOrganizing\Test\Domain\Entity;

use Localheinz\Test\Util\Helper;
use MeetupOrganizing\Domain\Model\Name;

/**
 * @internal
 */
final class NameTest extends \PHPUnit\Framework\TestCase
{
    use Helper;

    public function testItWrapsAString(): void
    {
        $nameText = $this->faker()->sentence;

        $name = Name::fromString($nameText);

        $this->assertEquals($nameText, (string) $name);
    }

    public function testItShouldBeANonEmptyString(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Name::fromString('');
    }
}
