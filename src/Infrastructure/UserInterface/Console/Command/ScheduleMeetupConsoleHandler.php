<?php

declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure\UserInterface\Console\Command;

use MeetupOrganizing\Application;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\IO\IO;

final class ScheduleMeetupConsoleHandler
{
    /**
     * @var Application\Command\ScheduleMeetupHandler
     */
    private $commandHandler;

    public function __construct(Application\Command\ScheduleMeetupHandler $commandHandler)
    {
        $this->commandHandler = $commandHandler;
    }

    public function handle(Args $args, IO $io): int
    {
        $command = new Application\Command\ScheduleMeetup();

        $command->name = $args->getArgument('name');
        $command->description = $args->getArgument('description');
        $command->scheduledFor = $args->getArgument('scheduledFor');

        $this->commandHandler->handle($command);

        $io->writeLine('<success>Scheduled the meetup successfully</success>');

        return 0;
    }
}
