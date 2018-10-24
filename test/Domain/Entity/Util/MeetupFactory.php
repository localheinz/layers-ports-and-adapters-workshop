<?php

declare(strict_types=1);

namespace MeetupOrganizing\Test\Domain\Entity\Util;

use Localheinz\Test\Util\Helper;
use MeetupOrganizing\Domain\Model\Description;
use MeetupOrganizing\Domain\Model\Meetup;
use MeetupOrganizing\Domain\Model\MeetupId;
use MeetupOrganizing\Domain\Model\Name;
use MeetupOrganizing\Domain\Model\ScheduledDate;

class MeetupFactory
{
    use Helper;

    public function pastMeetup(): Meetup
    {
        $faker = $this->faker();

        return Meetup::schedule(
            MeetupId::fromString($faker->uuid),
            Name::fromString($faker->sentence),
            Description::fromString($faker->sentence),
            ScheduledDate::fromPhpDateString('-5 days')
        );
    }

    public function upcomingMeetup(): Meetup
    {
        $faker = $this->faker();

        return Meetup::schedule(
            MeetupId::fromString($faker->uuid),
            Name::fromString($faker->sentence),
            Description::fromString($faker->sentence),
            ScheduledDate::fromPhpDateString('+5 days')
        );
    }

    public function someMeetup(): Meetup
    {
        return $this->upcomingMeetup();
    }
}
