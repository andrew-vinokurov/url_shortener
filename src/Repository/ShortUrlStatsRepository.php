<?php

namespace App\Repository;

use App\Entity\ShortUrl;
use App\Entity\ShortUrlStats;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ShortUrlStats|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShortUrlStats|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShortUrlStats[]    findAll()
 * @method ShortUrlStats[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShortUrlStatsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShortUrlStats::class);
    }

    /**
     * @param ShortUrl $shortUrl
     * @param int $limit
     * @param int $offset
     * @return ShortUrlStats[]
     */
    public function findByUrl(ShortUrl $shortUrl, int $limit = 50, int $offset = 0): array
    {
        return $this->findBy(['url' => $shortUrl], ['id' => Criteria::DESC], $limit, $offset);
    }

    public function _create(ShortUrlStats $shortUrlStats)
    {
        $this->getEntityManager()->persist($shortUrlStats);
        $this->getEntityManager()->flush();
    }

    public function _save(ShortUrlStats $shortUrlStats)
    {
        $this->getEntityManager()->flush();
    }
}
