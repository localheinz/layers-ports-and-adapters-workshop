<?php

declare(strict_types=1);

namespace MeetupOrganizing\Test\Domain\Entity;

use Localheinz\Test\Util\Helper;
use MeetupOrganizing\Domain\Model\ScheduledDate;

/**
 * @internal
 */
final class ScheduledDateTest extends \PHPUnit\Framework\TestCase
{
    use Helper;

    public function testItNormalizesTheDateToAtomFormat(): void
    {
        $date = $this->faker()->dateTime->format('Y-m-d H:i:s');

        $scheduledDate = ScheduledDate::fromPhpDateString($date);

        $this->assertEquals(
            new \DateTimeImmutable($date),
            $scheduledDate->toDateTimeImmutable()
        );
    }

    public function testItCanBeCreatedFromAPhpDateTimeString(): void
    {
        $scheduledDate = ScheduledDate::fromPhpDateString('+1 day');

        $this->assertTrue($scheduledDate->isInTheFuture(new \DateTimeImmutable('now')));
    }

    public function testItKnowsWhenADateIsInThePast(): void
    {
        $scheduledDate = ScheduledDate::fromPhpDateString('-1 day');

        $this->assertFalse($scheduledDate->isInTheFuture(new \DateTimeImmutable('now')));
    }

    public function testItCanBeCreatedFromAPhpDateTimeImmutable(): void
    {
        $date = $this->faker()->dateTime->format('Y-m-d H:i:s');

        $scheduledDate = ScheduledDate::fromDateTime(new \DateTimeImmutable($date));

        $this->assertEquals(
            new \DateTimeImmutable($date),
            $scheduledDate->toDateTimeImmutable()
        );
    }
}
