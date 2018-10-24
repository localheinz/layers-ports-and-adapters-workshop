<?php

declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure\Persistence\Filesystem;

use MeetupOrganizing\Domain;
use MeetupOrganizing\Domain\Model\MeetupId;
use NaiveSerializer\Serializer;
use Ramsey\Uuid\Uuid;

final class MeetupRepository implements Domain\Model\MeetupRepository
{
    /**
     * @var string
     */
    private $filePath;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    public function add(Domain\Model\Meetup $meetup): void
    {
        $meetups = $this->persistedMeetups();
        $meetups[] = $meetup;

        \file_put_contents($this->filePath, Serializer::serialize($meetups));
    }

    public function byId(Domain\Model\MeetupId $id): Domain\Model\Meetup
    {
        foreach ($this->persistedMeetups() as $meetup) {
            if ($meetup->id()->equals($id)) {
                return $meetup;
            }
        }

        throw new \RuntimeException('Meetup not found');
    }

    public function upcomingMeetups(\DateTimeImmutable $now): array
    {
        return \array_values(\array_filter($this->persistedMeetups(), function (Domain\Model\Meetup $meetup) use ($now) {
            return $meetup->isUpcoming($now);
        }));
    }

    /**
     * @param \DateTimeImmutable $now
     *
     * @return Domain\Model\Meetup[]
     */
    public function pastMeetups(\DateTimeImmutable $now): array
    {
        return \array_values(\array_filter($this->persistedMeetups(), function (Domain\Model\Meetup $meetup) use ($now) {
            return !$meetup->isUpcoming($now);
        }));
    }

    public function allMeetups(): array
    {
        return $this->persistedMeetups();
    }

    public function deleteAll(): void
    {
        \file_put_contents($this->filePath, '[]');
    }

    public function nextIdentifier(): MeetupId
    {
        return MeetupId::fromString(Uuid::uuid4()->toString());
    }

    /**
     * @return Domain\Model\Meetup[]
     */
    private function persistedMeetups(): array
    {
        if (!\file_exists($this->filePath)) {
            return [];
        }

        $rawJson = \file_get_contents($this->filePath);

        if (empty($rawJson)) {
            return [];
        }

        return Serializer::deserialize(Domain\Model\Meetup::class . '[]', $rawJson);
    }
}
