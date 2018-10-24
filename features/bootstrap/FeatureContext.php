<?php

declare(strict_types=1);

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use MeetupOrganizing\Domain;

/**
 * Defines application features from the specific context.
 */
final class FeatureContext implements Context, SnippetAcceptingContext
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @BeforeFeature
     */
    public static function purgeDatabase(): void
    {
        $container = require __DIR__ . '/../../app/container.php';

        /** @var Domain\Model\MeetupRepository $meetupRepository */
        $meetupRepository = $container[Domain\Model\MeetupRepository::class];

        $meetupRepository->deleteAll();
    }
}
