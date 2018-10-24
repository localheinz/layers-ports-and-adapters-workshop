<?php

declare(strict_types=1);

namespace MeetupOrganizing\Test\Infrastructure\Filesystem;

use MeetupOrganizing\Infrastructure\Filesystem\MeetupRepository;
use MeetupOrganizing\Test\Domain\Entity\Util\MeetupFactory;

/**
 * @internal
 */
final class MeetupRepositoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var MeetupRepository
     */
    private $repository;

    private $filePath;

    protected function setUp()
    {
        $this->filePath = \tempnam(\sys_get_temp_dir(), 'meetups');
        $this->repository = new MeetupRepository($this->filePath);
    }

    protected function tearDown()
    {
        \unlink($this->filePath);
    }

    public function testItPersistsAndRetrievesMeetups(): void
    {
        $originalMeetup = MeetupFactory::someMeetup();
        $this->repository->add($originalMeetup);

        $this->assertGreaterThanOrEqual(1, $originalMeetup->id());

        $restoredMeetup = $this->repository->byId($originalMeetup->id());

        $this->assertEquals($originalMeetup, $restoredMeetup);
    }

    public function testItsInitialStateIsValid(): void
    {
        $this->assertSame(
            [],
            $this->repository->upcomingMeetups(new \DateTimeImmutable())
        );
    }

    public function testItListsUpcomingMeetups(): void
    {
        $now = new \DateTimeImmutable();
        $pastMeetup = MeetupFactory::pastMeetup();
        $this->repository->add($pastMeetup);
        $upcomingMeetup = MeetupFactory::upcomingMeetup();
        $this->repository->add($upcomingMeetup);

        $this->assertEquals(
            [
                $upcomingMeetup,
            ],
            $this->repository->upcomingMeetups($now)
        );
    }

    public function testItListsPastMeetups(): void
    {
        $now = new \DateTimeImmutable();
        $pastMeetup = MeetupFactory::pastMeetup();
        $this->repository->add($pastMeetup);
        $upcomingMeetup = MeetupFactory::upcomingMeetup();
        $this->repository->add($upcomingMeetup);

        $this->assertEquals(
            [
                $pastMeetup,
            ],
            $this->repository->pastMeetups($now)
        );
    }

    public function testItCanDeleteAllMeetups(): void
    {
        $meetup = MeetupFactory::upcomingMeetup();
        $this->repository->add($meetup);
        $this->assertEquals([$meetup], $this->repository->allMeetups());

        $this->repository->deleteAll();

        $this->assertEquals([], $this->repository->upcomingMeetups(new \DateTimeImmutable()));
        $this->assertEquals([], $this->repository->pastMeetups(new \DateTimeImmutable()));
    }
}
