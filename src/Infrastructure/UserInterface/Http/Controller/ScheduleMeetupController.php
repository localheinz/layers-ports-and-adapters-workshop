<?php

declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure\UserInterface\Http\Controller;

use MeetupOrganizing\Application;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

final class ScheduleMeetupController
{
    /**
     * @var TemplateRendererInterface
     */
    private $renderer;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var Application\Command\ScheduleMeetupHandler
     */
    private $commandHandler;

    public function __construct(
        TemplateRendererInterface $renderer,
        RouterInterface $router,
        Application\Command\ScheduleMeetupHandler $commandHandler
    ) {
        $this->renderer = $renderer;
        $this->router = $router;
        $this->commandHandler = $commandHandler;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next): ResponseInterface
    {
        $formErrors = [];
        $submittedData = [];

        if ('POST' === $request->getMethod()) {
            $submittedData = $request->getParsedBody();

            if (empty($submittedData['name'])) {
                $formErrors['name'][] = 'Provide a name';
            }

            if (empty($submittedData['description'])) {
                $formErrors['description'][] = 'Provide a description';
            }

            if (empty($submittedData['scheduledFor'])) {
                $formErrors['scheduledFor'][] = 'Provide a scheduled for date';
            }

            if (empty($formErrors)) {
                $command = new Application\Command\ScheduleMeetup();

                $command->name = $submittedData['name'];
                $command->description = $submittedData['description'];
                $command->scheduledFor = $submittedData['scheduledFor'];

                $meetup = $this->commandHandler->handle($command);

                return new RedirectResponse(
                    $this->router->generateUri(
                        'meetup_details',
                        [
                            'id' => $meetup->id(),
                        ]
                    )
                );
            }
        }

        $response->getBody()->write(
            $this->renderer->render(
                'schedule-meetup.html.twig',
                [
                    'submittedData' => $submittedData,
                    'formErrors' => $formErrors,
                ]
            )
        );

        return $response;
    }
}
