<?php

declare(strict_types=1);

namespace MeetupOrganizing\Test\Domain\Entity;

use MeetupOrganizing\Domain\Model\ScheduledDate;

/**
 * @internal
 */
final class ScheduledDateTest extends \PHPUnit\Framework\TestCase
{
    public function testItNormalizesTheDateToAtomFormat(): void
    {
        $scheduledDate = ScheduledDate::fromPhpDateString('2017-01-01 20:00');

        $this->assertEquals(
            new \DateTimeImmutable('2017-01-01 20:00'),
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
        $scheduledDate = ScheduledDate::fromDateTime(new \DateTimeImmutable('2017-01-01 20:00'));

        $this->assertEquals(
            new \DateTimeImmutable('2017-01-01 20:00'),
            $scheduledDate->toDateTimeImmutable()
        );
    }
}
