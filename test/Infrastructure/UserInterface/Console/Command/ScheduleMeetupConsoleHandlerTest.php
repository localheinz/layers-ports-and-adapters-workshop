<?php

declare(strict_types=1);

namespace MeetupOrganizing\Test\Infrastructure\UserInterface\Console\Command;

use MeetupOrganizing\Infrastructure\UserInterface\Console\Command\MeetupApplicationConfig;
use Webmozart\Console\Args\StringArgs;
use Webmozart\Console\ConsoleApplication;
use Webmozart\Console\IO\OutputStream\BufferedOutputStream;

/**
 * @internal
 */
final class ScheduleMeetupConsoleHandlerTest extends \PHPUnit\Framework\TestCase
{
    public function testItSchedulesAMeetup(): void
    {
        $container = require __DIR__ . '/../../../../../app/container.php';

        $config = new MeetupApplicationConfig($container);
        $config->setTerminateAfterRun(false);
        $cli = new ConsoleApplication($config);

        $output = new BufferedOutputStream();
        $args = new StringArgs('schedule Akeneo Meetup "2018-04-20 20:00"');
        $cli->run($args, null, $output);

        $this->assertContains(
            'Scheduled the meetup successfully',
            $output->fetch()
        );
    }
}
