<?php

declare(strict_types=1);

use Interop\Container\ContainerInterface;
use MeetupOrganizing\Domain;
use MeetupOrganizing\Infrastructure\Persistence\Filesystem\MeetupRepository;
use MeetupOrganizing\Infrastructure\UserInterface\Console\Command\ScheduleMeetupConsoleHandler;
use MeetupOrganizing\Infrastructure\UserInterface\Http\Controller\ListMeetupsController;
use MeetupOrganizing\Infrastructure\UserInterface\Http\Controller\MeetupDetailsController;
use MeetupOrganizing\Infrastructure\UserInterface\Http\Controller\ScheduleMeetupController;
use MeetupOrganizing\Infrastructure\UserInterface\Http\Views\TwigTemplates;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Debug\Debug;
use Symfony\Component\Debug\ErrorHandler;
use Xtreamwayz\Pimple\Container;
use Zend\Expressive\Application;
use Zend\Expressive\Container\ApplicationFactory;
use Zend\Expressive\Helper\ServerUrlHelper;
use Zend\Expressive\Helper\UrlHelper;
use Zend\Expressive\Router\FastRouteRouter;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Expressive\Twig\TwigRendererFactory;

Debug::enable();
ErrorHandler::register();

$container = new Container();

$container['config'] = [
    'debug' => true,
    'templates' => [
        'extension' => 'html.twig',
        'paths' => [
            TwigTemplates::getPath(),
        ],
    ],
    'twig' => [
        'extensions' => [
        ],
    ],
    'routes' => [
        [
            'name' => 'list_meetups',
            'path' => '/',
            'middleware' => ListMeetupsController::class,
            'allowed_methods' => ['GET'],
        ],
        [
            'name' => 'meetup_details',
            'path' => '/meetup/{id}',
            'middleware' => MeetupDetailsController::class,
            'allowed_methods' => ['GET'],
        ],
        [
            'name' => 'schedule_meetup',
            'path' => '/schedule-meetup',
            'middleware' => ScheduleMeetupController::class,
            'allowed_methods' => ['GET', 'POST'],
        ],
    ],
];

/*
 * Zend Expressive Application
 */
$container['Zend\Expressive\FinalHandler'] = function () {
    return function (RequestInterface $request, ResponseInterface $response, $err = null) {
        if ($err instanceof \Throwable) {
            throw $err;
        }
    };
};
$container[RouterInterface::class] = function () {
    return new FastRouteRouter();
};
$container[Application::class] = new ApplicationFactory();

/*
 * Templating
 */
$container[TemplateRendererInterface::class] = new TwigRendererFactory();
$container[ServerUrlHelper::class] = function () {
    return new ServerUrlHelper();
};
$container[UrlHelper::class] = function (ContainerInterface $container) {
    return new UrlHelper($container[RouterInterface::class]);
};

/*
 * Persistence
 */
$container[Domain\Entity\MeetupRepository::class] = function () {
    return new MeetupRepository(__DIR__ . '/../var/meetups.txt');
};

/**
 * Application.
 */
$container[\MeetupOrganizing\Application\Command\ScheduleMeetupHandler::class] = function (ContainerInterface $container) {
    return new \MeetupOrganizing\Application\Command\ScheduleMeetupHandler($container->get(Domain\Entity\MeetupRepository::class));
};

/*
 * Controllers
 */
$container[ScheduleMeetupController::class] = function (ContainerInterface $container) {
    return new ScheduleMeetupController(
        $container->get(TemplateRendererInterface::class),
        $container->get(RouterInterface::class),
        $container->get(\MeetupOrganizing\Application\Command\ScheduleMeetupHandler::class)
    );
};
$container[ListMeetupsController::class] = function (ContainerInterface $container) {
    return new ListMeetupsController(
        $container->get(Domain\Entity\MeetupRepository::class),
        $container->get(TemplateRendererInterface::class)
    );
};
$container[MeetupDetailsController::class] = function (ContainerInterface $container) {
    return new MeetupDetailsController(
        $container->get(Domain\Entity\MeetupRepository::class),
        $container->get(TemplateRendererInterface::class)
    );
};

/*
 * CLI
 */
$container[ScheduleMeetupConsoleHandler::class] = function (ContainerInterface $container) {
    return new ScheduleMeetupConsoleHandler($container->get(\MeetupOrganizing\Application\Command\ScheduleMeetupHandler::class));
};

return $container;
