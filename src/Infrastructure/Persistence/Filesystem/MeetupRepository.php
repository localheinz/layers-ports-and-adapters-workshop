<?php

declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure\Persistence\Filesystem;

use MeetupOrganizing\Domain;
use NaiveSerializer\Serializer;

final class MeetupRepository implements Domain\Entity\MeetupRepository
{
    /**
     * @var string
     */
    private $filePath;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    public function add(Domain\Entity\Meetup $meetup): void
    {
        $meetups = $this->persistedMeetups();
        $id = \count($meetups) + 1;
        $meetup->setId($id);
        $meetups[] = $meetup;
        \file_put_contents($this->filePath, Serializer::serialize($meetups));
    }

    public function byId(int $id): Domain\Entity\Meetup
    {
        foreach ($this->persistedMeetups() as $meetup) {
            if ($meetup->id() === $id) {
                return $meetup;
            }
        }

        throw new \RuntimeException('Meetup not found');
    }

    public function upcomingMeetups(\DateTimeImmutable $now): array
    {
        return \array_values(\array_filter($this->persistedMeetups(), function (Domain\Entity\Meetup $meetup) use ($now) {
            return $meetup->isUpcoming($now);
        }));
    }

    /**
     * @param \DateTimeImmutable $now
     *
     * @return Domain\Entity\Meetup[]
     */
    public function pastMeetups(\DateTimeImmutable $now): array
    {
        return \array_values(\array_filter($this->persistedMeetups(), function (Domain\Entity\Meetup $meetup) use ($now) {
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

    /**
     * @return Domain\Entity\Meetup[]
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

        return Serializer::deserialize(Domain\Entity\Meetup::class . '[]', $rawJson);
    }
}
