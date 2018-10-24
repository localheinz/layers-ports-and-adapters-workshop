<?php

declare(strict_types=1);

namespace MeetupOrganizing\Test\Domain\Entity;

use Localheinz\Test\Util\Helper;
use MeetupOrganizing\Domain\Model\MeetupId;

/**
 * @internal
 */
final class MeetupIdTest extends \PHPUnit\Framework\TestCase
{
    use Helper;

    public function testItCanBeConstructedFromAStringAndRevertedBackToIt(): void
    {
        $id = $this->faker()->uuid;

        $meetupId = MeetupId::fromString($id);

        $this->assertSame($id, (string) $meetupId);
    }

    public function testItCanBeComparedToAnotherMeetupId(): void
    {
        $id = $this->faker()->uuid;

        $meetupId1 = MeetupId::fromString($id);
        $meetupId2 = MeetupId::fromString($id);

        $this->assertTrue($meetupId1->equals($meetupId2));
    }
}
