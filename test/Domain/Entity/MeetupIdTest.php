<?php

declare(strict_types=1);

namespace MeetupOrganizing\Test\Domain\Entity;

use MeetupOrganizing\Domain\Entity\MeetupId;

/**
 * @internal
 */
final class MeetupIdTest extends \PHPUnit\Framework\TestCase
{
    public function testItCanBeConstructedFromAStringAndRevertedBackToIt(): void
    {
        $id = '7d7fd0b2-0cb5-42ac-b697-3f7bfce24df9';
        $meetupId = MeetupId::fromString($id);
        $this->assertSame($id, (string) $meetupId);
    }

    public function testItCanBeComparedToAnotherMeetupId(): void
    {
        $meetupId1 = MeetupId::fromString('3a021c08-ad15-43aa-aba3-8626fecd39a7');
        $meetupId2 = MeetupId::fromString('3a021c08-ad15-43aa-aba3-8626fecd39a7');
        $this->assertTrue($meetupId1->equals($meetupId2));
    }
}
