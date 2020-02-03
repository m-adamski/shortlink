<?php

namespace App\Controller;

use App\Repository\ShortURLRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class RedirectController extends AbstractController {

    /**
     * @var ShortURLRepository
     */
    protected $shortURLRepository;

    /**
     * RedirectController constructor.
     *
     * @param ShortURLRepository $shortURLRepository
     */
    public function __construct(ShortURLRepository $shortURLRepository) {
        $this->shortURLRepository = $shortURLRepository;
    }

    /**
     * Redirect to destination URL.
     *
     * @param string $sourceName
     * @return Response
     */
    public function index(string $sourceName): Response {
        if (null !== ($shortURL = $this->shortURLRepository->getOneBySourceName($sourceName))) {
            $currentDate = new DateTime();

            // Check if link is active and valid
            if (
                true === $shortURL->isActive() &&
                (null !== $shortURL->getValidUntil() && $currentDate < $shortURL->getValidUntil()) &&
                (null !== $shortURL->getValidSince() && $currentDate > $shortURL->getValidSince())
            ) {
                return $this->render("redirect.html.twig", [
                    "destinationURL" => $shortURL->getDestinationURL()
                ]);
            }
        }

        throw $this->createNotFoundException();
    }
}
