<?php

declare(strict_types=1);

namespace MeetupOrganizing\Test\Domain\Entity;

use MeetupOrganizing\Domain\Model\Description;
use MeetupOrganizing\Domain\Model\Meetup;
use MeetupOrganizing\Domain\Model\MeetupId;
use MeetupOrganizing\Domain\Model\Name;
use MeetupOrganizing\Domain\Model\ScheduledDate;
use Ramsey\Uuid\Uuid;

/**
 * @internal
 */
final class MeetupTest extends \PHPUnit\Framework\TestCase
{
    public function testItCanBeScheduledWithJustANameDescriptionAndDate(): void
    {
        $id = MeetupId::fromString(Uuid::uuid4()->toString());
        $name = Name::fromString('Name');
        $description = Description::fromString('Description');
        $scheduledFor = ScheduledDate::fromPhpDateString('now');

        $meetup = Meetup::schedule(
            $id,
            $name,
            $description,
            $scheduledFor
        );

        $this->assertEquals($id, $meetup->id());
        $this->assertEquals($name, $meetup->name());
        $this->assertEquals($description, $meetup->description());
        $this->assertEquals($scheduledFor, $meetup->scheduledFor());
    }

    public function testCanDetermineWhetherOrNotItIsUpcoming(): void
    {
        $now = new \DateTimeImmutable();

        $pastMeetup = Meetup::schedule(
            MeetupId::fromString(Uuid::uuid4()->toString()),
            Name::fromString('Name'),
            Description::fromString('Description'),
            ScheduledDate::fromPhpDateString('-5 days')
        );

        $this->assertFalse($pastMeetup->isUpcoming($now));

        $upcomingMeetup = Meetup::schedule(
            MeetupId::fromString(Uuid::uuid4()->toString()),
            Name::fromString('Name'),
            Description::fromString('Description'),
            ScheduledDate::fromPhpDateString('+5 days')
        );
        $this->assertTrue($upcomingMeetup->isUpcoming($now));
    }
}
