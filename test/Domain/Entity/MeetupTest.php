<?php

declare(strict_types=1);

namespace MeetupOrganizing\Test\Domain\Entity;

use Localheinz\Test\Util\Helper;
use MeetupOrganizing\Domain\Model\Description;
use MeetupOrganizing\Domain\Model\Meetup;
use MeetupOrganizing\Domain\Model\MeetupId;
use MeetupOrganizing\Domain\Model\Name;
use MeetupOrganizing\Domain\Model\ScheduledDate;

/**
 * @internal
 */
final class MeetupTest extends \PHPUnit\Framework\TestCase
{
    use Helper;

    public function testItCanBeScheduledWithJustANameDescriptionAndDate(): void
    {
        $faker = $this->faker();

        $id = MeetupId::fromString($faker->uuid);
        $name = Name::fromString($faker->sentence);
        $description = Description::fromString($faker->sentence);
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
        $faker = $this->faker();

        $now = new \DateTimeImmutable();

        $pastMeetup = Meetup::schedule(
            MeetupId::fromString($faker->uuid),
            Name::fromString($faker->sentence),
            Description::fromString($faker->sentence),
            ScheduledDate::fromPhpDateString('-5 days')
        );

        $this->assertFalse($pastMeetup->isUpcoming($now));

        $upcomingMeetup = Meetup::schedule(
            MeetupId::fromString($faker->uuid),
            Name::fromString($faker->sentence),
            Description::fromString($faker->sentence),
            ScheduledDate::fromPhpDateString('+5 days')
        );

        $this->assertTrue($upcomingMeetup->isUpcoming($now));
    }
}
