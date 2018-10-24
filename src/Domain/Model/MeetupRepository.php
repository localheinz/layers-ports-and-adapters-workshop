<?php

declare(strict_types=1);

namespace MeetupOrganizing\Domain\Model;

interface MeetupRepository
{
    public function add(Meetup $meetup): void;

    public function byId(MeetupId $id): Meetup;

    /**
     * @param \DateTimeImmutable $now
     *
     * @return Meetup[]
     */
    public function upcomingMeetups(\DateTimeImmutable $now): array;

    /**
     * @param \DateTimeImmutable $now
     *
     * @return Meetup[]
     */
    public function pastMeetups(\DateTimeImmutable $now): array;

    /**
     * @return Meetup[]
     */
    public function allMeetups(): array;

    public function deleteAll(): void;

    public function nextIdentifier(): MeetupId;
}
