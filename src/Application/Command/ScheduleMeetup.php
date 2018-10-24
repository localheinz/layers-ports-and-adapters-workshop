<?php

declare(strict_types=1);

namespace MeetupOrganizing\Application\Command;

final class ScheduleMeetup
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $scheduledFor;
}
