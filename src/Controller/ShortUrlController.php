<?php

namespace App\Controller;

use App\Entity\ShortUrl;
use App\Form\CreateShortUrlType;
use App\Repository\ShortUrlRepository;
use App\Repository\ShortUrlStatsRepository;
use App\Services\ShortUrlService;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ShortUrlController extends AbstractController
{
    /**
     * @var ShortUrlService
     */
    private $shortUrlService;
    /**
     * @var ShortUrlRepository
     */
    private $shortUrlRepository;
    /**
     * @var ShortUrlStatsRepository
     */
    private $shortUrlStatsRepository;

    public function __construct(
        ShortUrlRepository $shortUrlRepository,
        ShortUrlStatsRepository $shortUrlStatsRepository,
        ShortUrlService $shortUrlService
    ) {
        $this->shortUrlRepository = $shortUrlRepository;
        $this->shortUrlStatsRepository = $shortUrlStatsRepository;
        $this->shortUrlService = $shortUrlService;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request): Response
    {
        $shortUrl = new ShortUrl();
        $form = $this->createForm(CreateShortUrlType::class, $shortUrl);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $shortUrl->setHashUrl($this->shortUrlService->createHashUrl($shortUrl->getUrl()));
            $em = $this->getDoctrine()->getManager();
            $em->persist($shortUrl);
            $em->flush();

            return $this->redirectToRoute("shortUrlList");
        }

        return $this->render(
            'shortUrlCreate.html.twig',
            [
                'title' => 'Create short URL',
                'form'  => $form->createView(),
            ]
        );
    }

    /**
     * @return Response
     */
    public function listAction(): Response
    {
        return $this->render(
            'shortUrlList.html.twig',
            [
                'title'  => 'URLs list',
                'domain' => $_SERVER['SHORT_URL_DOMAIN'],
                'urls'   => $this->shortUrlRepository->findAllByLimit(),
            ]
        );
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function statsAction(Request $request): Response
    {
        $shortUrl = $this->shortUrlRepository->findByHashUrl($request->get('hashUrl'));
        if (!$shortUrl instanceof ShortUrl) {
            return new Response('URL not found', Response::HTTP_NOT_FOUND);
        }
        $stats = $this->shortUrlStatsRepository->findByUrl($shortUrl);
        if (!$stats) {
            return new Response('URL has not been used yet! ', Response::HTTP_OK);
        }

        return $this->render(
            'shortUrlStats.html.twig',
            [
                'title' => 'Stats for URL',
                'url'   => $shortUrl,
                'stats' => $this->shortUrlService->prepareStatsForRendering($stats),
            ]
        );
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function redirectFromShortURLAction(Request $request): Response
    {
        $shortUrl = $this->shortUrlRepository->findByHashUrl($request->get('hashUrl'));
        if (!$shortUrl instanceof ShortUrl) {
            return new Response('URL not found', Response::HTTP_NOT_FOUND);
        }
        if ($shortUrl->getExpireAt() < new DateTime()) {
            return new Response('URL has expired', Response::HTTP_NOT_FOUND);
        }
        $this->shortUrlService->saveClickStats($shortUrl, $request);
        header('Location: '.$shortUrl->getUrl(), true, Response::HTTP_MOVED_PERMANENTLY);

        return new Response('OK', Response::HTTP_OK);
    }
}