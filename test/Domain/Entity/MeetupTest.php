<?php

declare(strict_types=1);

namespace MeetupOrganizing\Test\Domain\Entity;

use MeetupOrganizing\Domain\Entity\Description;
use MeetupOrganizing\Domain\Entity\Meetup;
use MeetupOrganizing\Domain\Entity\Name;
use MeetupOrganizing\Domain\Entity\ScheduledDate;

/**
 * @internal
 */
final class MeetupTest extends \PHPUnit\Framework\TestCase
{
    public function testItCanBeScheduledWithJustANameDescriptionAndDate(): void
    {
        $name = Name::fromString('Name');
        $description = Description::fromString('Description');
        $scheduledFor = ScheduledDate::fromPhpDateString('now');

        $meetup = Meetup::schedule($name, $description, $scheduledFor);

        $this->assertEquals($name, $meetup->name());
        $this->assertEquals($description, $meetup->description());
        $this->assertEquals($scheduledFor, $meetup->scheduledFor());
    }

    public function testCanDetermineWhetherOrNotItIsUpcoming(): void
    {
        $now = new \DateTimeImmutable();

        $pastMeetup = Meetup::schedule(
            Name::fromString('Name'),
            Description::fromString('Description'),
            ScheduledDate::fromPhpDateString('-5 days')
        );
        $this->assertFalse($pastMeetup->isUpcoming($now));

        $upcomingMeetup = Meetup::schedule(
            Name::fromString('Name'),
            Description::fromString('Description'),
            ScheduledDate::fromPhpDateString('+5 days')
        );
        $this->assertTrue($upcomingMeetup->isUpcoming($now));
    }
}
