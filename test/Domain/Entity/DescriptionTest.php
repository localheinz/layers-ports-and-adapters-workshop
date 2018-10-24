<?php

declare(strict_types=1);

namespace MeetupOrganizing\Test\Domain\Entity;

use Localheinz\Test\Util\Helper;
use MeetupOrganizing\Domain\Model\Description;

/**
 * @internal
 */
final class DescriptionTest extends \PHPUnit\Framework\TestCase
{
    use Helper;

    public function testItWrapsAString(): void
    {
        $descriptionText = $this->faker()->sentence;

        $description = Description::fromString($descriptionText);

        $this->assertEquals($descriptionText, (string) $description);
    }

    public function testItShouldBeANonEmptyString(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Description::fromString('');
    }
}
