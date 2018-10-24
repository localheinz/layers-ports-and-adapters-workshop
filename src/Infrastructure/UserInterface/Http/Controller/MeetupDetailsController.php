<?php

declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure\UserInterface\Http\Controller;

use MeetupOrganizing\Domain;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

final class MeetupDetailsController
{
    /**
     * @var Domain\Model\MeetupRepository
     */
    private $meetupRepository;

    /**
     * @var TemplateRendererInterface
     */
    private $renderer;

    public function __construct(Domain\Model\MeetupRepository $meetupRepository, TemplateRendererInterface $renderer)
    {
        $this->meetupRepository = $meetupRepository;
        $this->renderer = $renderer;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $out = null): ResponseInterface
    {
        $meetup = $this->meetupRepository->byId((int) $request->getAttribute('id'));

        $response->getBody()->write($this->renderer->render('meetup-details.html.twig', [
            'meetup' => $meetup,
        ]));

        return $response;
    }
}
