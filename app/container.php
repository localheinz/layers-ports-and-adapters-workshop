<?php

declare(strict_types=1);

use Interop\Container\ContainerInterface;
use MeetupOrganizing\Application;
use MeetupOrganizing\Domain;
use MeetupOrganizing\Infrastructure;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Debug\Debug;
use Symfony\Component\Debug\ErrorHandler;
use Xtreamwayz\Pimple\Container;
use Zend\Expressive;

Debug::enable();
ErrorHandler::register();

$container = new Container();

$container['config'] = [
    'debug' => true,
    'templates' => [
        'extension' => 'html.twig',
        'paths' => [
            Infrastructure\UserInterface\Http\Views\TwigTemplates::getPath(),
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
            'middleware' => Infrastructure\UserInterface\Http\Controller\ListMeetupsController::class,
            'allowed_methods' => ['GET'],
        ],
        [
            'name' => 'meetup_details',
            'path' => '/meetup/{id}',
            'middleware' => Infrastructure\UserInterface\Http\Controller\MeetupDetailsController::class,
            'allowed_methods' => ['GET'],
        ],
        [
            'name' => 'schedule_meetup',
            'path' => '/schedule-meetup',
            'middleware' => Infrastructure\UserInterface\Http\Controller\ScheduleMeetupController::class,
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
$container[Expressive\Router\RouterInterface::class] = function () {
    return new Expressive\Router\FastRouteRouter();
};
$container[Expressive\Application::class] = new Expressive\Container\ApplicationFactory();

/*
 * Templating
 */
$container[Expressive\Template\TemplateRendererInterface::class] = new Expressive\Twig\TwigRendererFactory();
$container[Expressive\Helper\ServerUrlHelper::class] = function () {
    return new Expressive\Helper\ServerUrlHelper();
};
$container[Expressive\Helper\UrlHelper::class] = function (ContainerInterface $container) {
    return new Expressive\Helper\UrlHelper($container[Expressive\Router\RouterInterface::class]);
};

/*
 * Application: Command Handler
 */
$container[Application\Command\ScheduleMeetupHandler::class] = function (ContainerInterface $container) {
    return new Application\Command\ScheduleMeetupHandler($container->get(Domain\Model\MeetupRepository::class));
};

/*
 * Infrastructure: Console
 */
$container[Infrastructure\UserInterface\Console\Command\ScheduleMeetupConsoleHandler::class] = function (ContainerInterface $container) {
    return new Infrastructure\UserInterface\Console\Command\ScheduleMeetupConsoleHandler($container->get(Application\Command\ScheduleMeetupHandler::class));
};

/*
 * Infrastructure: Http
 */

$container[Infrastructure\UserInterface\Http\Controller\ScheduleMeetupController::class] = function (ContainerInterface $container) {
    return new Infrastructure\UserInterface\Http\Controller\ScheduleMeetupController(
        $container->get(Expressive\Template\TemplateRendererInterface::class),
        $container->get(Expressive\Router\RouterInterface::class),
        $container->get(Application\Command\ScheduleMeetupHandler::class)
    );
};

$container[Infrastructure\UserInterface\Http\Controller\ListMeetupsController::class] = function (ContainerInterface $container) {
    return new Infrastructure\UserInterface\Http\Controller\ListMeetupsController(
        $container->get(Domain\Model\MeetupRepository::class),
        $container->get(Expressive\Template\TemplateRendererInterface::class)
    );
};

$container[Infrastructure\UserInterface\Http\Controller\MeetupDetailsController::class] = function (ContainerInterface $container) {
    return new Infrastructure\UserInterface\Http\Controller\MeetupDetailsController(
        $container->get(Domain\Model\MeetupRepository::class),
        $container->get(Expressive\Template\TemplateRendererInterface::class)
    );
};

/*
 * Infrastructure: Persistence
 */
$container[Domain\Model\MeetupRepository::class] = function () {
    return new Infrastructure\Persistence\Filesystem\MeetupRepository(__DIR__ . '/../var/meetups.txt');
};

return $container;
