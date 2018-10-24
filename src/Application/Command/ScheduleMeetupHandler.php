<?php

declare(strict_types=1);

namespace MeetupOrganizing\Application\Command;

use MeetupOrganizing\Domain;

final class ScheduleMeetupHandler
{
    /**
     * @var Domain\Entity\MeetupRepository
     */
    private $meetupRepository;

    public function __construct(Domain\Entity\MeetupRepository $meetupRepository)
    {
        $this->meetupRepository = $meetupRepository;
    }

    public function handle(ScheduleMeetup $command): Domain\Entity\Meetup
    {
        $meetup = Domain\Entity\Meetup::schedule(
            Domain\Entity\Name::fromString($command->name),
            Domain\Entity\Description::fromString($command->description),
            Domain\Entity\ScheduledDate::fromPhpDateString($command->scheduledFor)
        );

        $this->meetupRepository->add($meetup);

        return $meetup;
    }
}
