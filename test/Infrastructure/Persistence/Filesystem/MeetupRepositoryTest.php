<?php

declare(strict_types=1);

namespace MeetupOrganizing\Test\Infrastructure\Persistence\Filesystem;

use MeetupOrganizing\Infrastructure\Persistence\Filesystem\MeetupRepository;
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

    private $meetupFactory;

    protected function setUp()
    {
        $this->filePath = \tempnam(\sys_get_temp_dir(), 'meetups');
        $this->repository = new MeetupRepository($this->filePath);
        $this->meetupFactory = new MeetupFactory();
    }

    protected function tearDown()
    {
        \unlink($this->filePath);
    }

    public function testItPersistsAndRetrievesMeetups(): void
    {
        $originalMeetup = $this->meetupFactory->someMeetup();

        $this->repository->add($originalMeetup);

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
        $pastMeetup = $this->meetupFactory->pastMeetup();
        $this->repository->add($pastMeetup);
        $upcomingMeetup = $this->meetupFactory->upcomingMeetup();
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
        $pastMeetup = $this->meetupFactory->pastMeetup();
        $this->repository->add($pastMeetup);
        $upcomingMeetup = $this->meetupFactory->upcomingMeetup();
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
        $meetup = $this->meetupFactory->upcomingMeetup();
        $this->repository->add($meetup);
        $this->assertEquals([$meetup], $this->repository->allMeetups());

        $this->repository->deleteAll();

        $this->assertEquals([], $this->repository->upcomingMeetups(new \DateTimeImmutable()));
        $this->assertEquals([], $this->repository->pastMeetups(new \DateTimeImmutable()));
    }
}
