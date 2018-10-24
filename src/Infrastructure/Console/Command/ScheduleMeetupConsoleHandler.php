<?php

declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure\Console\Command;

use MeetupOrganizing\Domain\Entity\Description;
use MeetupOrganizing\Domain\Entity\Meetup;
use MeetupOrganizing\Domain\Entity\Name;
use MeetupOrganizing\Domain\Entity\ScheduledDate;
use MeetupOrganizing\Infrastructure\Filesystem\MeetupRepository;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\IO\IO;

final class ScheduleMeetupConsoleHandler
{
    /**
     * @var MeetupRepository
     */
    private $repository;

    public function __construct(MeetupRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(Args $args, IO $io): int
    {
        $meetup = Meetup::schedule(
            Name::fromString($args->getArgument('name')),
            Description::fromString($args->getArgument('description')),
            ScheduledDate::fromPhpDateString($args->getArgument('scheduledFor'))
        );
        $this->repository->add($meetup);

        $io->writeLine('<success>Scheduled the meetup successfully</success>');

        return 0;
    }
}
