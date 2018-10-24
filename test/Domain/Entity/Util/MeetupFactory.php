<?php

declare(strict_types=1);

namespace MeetupOrganizing\Test\Domain\Entity\Util;

use MeetupOrganizing\Domain\Model\Description;
use MeetupOrganizing\Domain\Model\Meetup;
use MeetupOrganizing\Domain\Model\MeetupId;
use MeetupOrganizing\Domain\Model\Name;
use MeetupOrganizing\Domain\Model\ScheduledDate;
use Ramsey\Uuid\Uuid;

class MeetupFactory
{
    public static function pastMeetup(): Meetup
    {
        return Meetup::schedule(
            MeetupId::fromString(Uuid::uuid4()->toString()),
            Name::fromString('Name'),
            Description::fromString('Description'),
            ScheduledDate::fromPhpDateString('-5 days')
        );
    }

    public static function upcomingMeetup(): Meetup
    {
        return Meetup::schedule(
            MeetupId::fromString(Uuid::uuid4()->toString()),
            Name::fromString('Name'),
            Description::fromString('Description'),
            ScheduledDate::fromPhpDateString('+5 days')
        );
    }

    public static function someMeetup(): Meetup
    {
        return self::upcomingMeetup();
    }
}
