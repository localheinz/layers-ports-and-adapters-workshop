<?php

declare(strict_types=1);

namespace MeetupOrganizing\Domain\Model;

final class ScheduledDate
{
    public const DATE_TIME_FORMAT = \DateTime::ATOM;

    /**
     * @var string
     */
    private $dateTime;

    private function __construct(string $dateTime)
    {
        $this->dateTime = $dateTime;
    }

    public function __toString(): string
    {
        return $this->dateTime;
    }

    public static function fromPhpDateString(string $phpDateString): self
    {
        try {
            $dateTimeImmutable = new \DateTimeImmutable($phpDateString);
        } catch (\Throwable $throwable) {
            throw new \InvalidArgumentException(
                'Invalid PHP date time format',
                null,
                $throwable
            );
        }

        return self::fromDateTime($dateTimeImmutable);
    }

    public static function fromDateTime(\DateTimeImmutable $dateTime): self
    {
        return new self($dateTime->format(self::DATE_TIME_FORMAT));
    }

    public function isInTheFuture(\DateTimeImmutable $now): bool
    {
        return $this->toDateTimeImmutable() > $now;
    }

    public function toDateTimeImmutable(): \DateTimeImmutable
    {
        return \DateTimeImmutable::createFromFormat(self::DATE_TIME_FORMAT, $this->dateTime);
    }
}
