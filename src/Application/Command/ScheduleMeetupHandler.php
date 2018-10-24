<?php

declare(strict_types=1);

namespace MeetupOrganizing\Application\Command;

use MeetupOrganizing\Domain;

final class ScheduleMeetupHandler
{
    /**
     * @var Domain\Model\MeetupRepository
     */
    private $meetupRepository;

    public function __construct(Domain\Model\MeetupRepository $meetupRepository)
    {
        $this->meetupRepository = $meetupRepository;
    }

    public function handle(ScheduleMeetup $command): Domain\Model\Meetup
    {
        $meetup = Domain\Model\Meetup::schedule(
            $this->meetupRepository->nextIdentifier(),
            Domain\Model\Name::fromString($command->name),
            Domain\Model\Description::fromString($command->description),
            Domain\Model\ScheduledDate::fromPhpDateString($command->scheduledFor)
        );

        $this->meetupRepository->add($meetup);

        return $meetup;
    }
}
