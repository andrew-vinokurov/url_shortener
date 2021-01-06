<?php

namespace App\Services;

use App\Entity\ShortUrl;
use App\Entity\ShortUrlStats;
use App\Repository\ShortUrlStatsRepository;
use Symfony\Component\HttpFoundation\Request;

class ShortUrlService
{
    /**
     * @var ShortUrlStatsRepository
     */
    private $shortUrlStatsRepository;

    public function __construct(ShortUrlStatsRepository $shortUrlStatsRepository)
    {
        $this->shortUrlStatsRepository = $shortUrlStatsRepository;
    }

    public function createHashUrl(string $url): string
    {
        return hash('crc32', $url.uniqid($_SERVER['REMOTE_ADDR'], true).random_int(1, 10000));
    }

    public function prepareStatsForRendering(array $stats): array
    {
        $response = [];

        foreach ($stats as $key => $stat) {
            array_push(
                $response,
                [
                    'userAgent' => $stat->getUserAgent(),
                    'ip'        => long2ip($stat->getClientIp()),
                    'created'   => $stat->getCreatedAt()->format('Y-m-d H:i:s'),
                ]
            );
        }

        return $response;
    }

    /**
     * @param ShortUrl $shortUrl
     * @param Request $request
     */
    public function saveClickStats(ShortUrl $shortUrl, Request $request): void
    {
        $shortUrlStats = new ShortUrlStats();
        $shortUrlStats->setUrl($shortUrl);
        $shortUrlStats->setUserAgent($request->server->get('HTTP_USER_AGENT'));
        $shortUrlStats->setClientIp(ip2long($request->server->get('REMOTE_ADDR')));

        $this->shortUrlStatsRepository->_create($shortUrlStats);
    }
}