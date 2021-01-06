<?php

namespace App\Repository;

use App\Entity\ShortUrl;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ShortUrl|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShortUrl|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShortUrl[]    findAll()
 * @method ShortUrl[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShortUrlRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShortUrl::class);
    }

    /**
     * @param int $limit
     * @return ShortUrl[]
     */
    public function findAllByLimit(int $limit = 50): array
    {
        return $this->findBy([], ['id' => Criteria::DESC], $limit);
    }

    /**
     * @param string $hashUrl
     * @return ShortUrl|null
     */
    public function findByHashUrl(string $hashUrl): ?ShortUrl
    {
        return $this->findOneBy(['hashUrl' => $hashUrl]);
    }

    public function _create(ShortUrl $shortUrl)
    {
        $this->getEntityManager()->persist($shortUrl);
        $this->getEntityManager()->flush();
    }

    public function _save(ShortUrl $shortUrl)
    {
        $this->getEntityManager()->flush();
    }
}
