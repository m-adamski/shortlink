<?php

namespace App\Repository;

use App\Entity\ShortURL;
use App\Helper\ShortURLHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Exception;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method ShortURL|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShortURL|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShortURL[]    findAll()
 * @method ShortURL[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShortURLRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, ShortURL::class);
    }

    /**
     * Get ShortURL with specified source name.
     *
     * @param string $sourceName
     * @return ShortURL|null
     */
    public function getOneBySourceName(string $sourceName): ?ShortURL {
        return $this->findOneBy(["sourceName" => $sourceName]);
    }

    /**
     * Get collection of items matching provided conditions.
     *
     * @param string|null $sourceName
     * @param string|null $destinationURL
     * @param bool        $onlyActive
     * @return ShortURL[]
     */
    public function search(?string $sourceName, ?string $destinationURL, bool $onlyActive) {
        $queryBuilder = $this->createQueryBuilder("short_url");

        // Add sourceName condition
        if (null !== $sourceName && "" !== $sourceName) {
            $queryBuilder->andWhere($queryBuilder->expr()->like("short_url.sourceName", ":sourceName"))
                ->setParameter("sourceName", "%" . $sourceName . "%");
        }

        // Add destinationURL condition
        if (null !== $destinationURL && "" !== $destinationURL) {
            $queryBuilder->andWhere($queryBuilder->expr()->like("short_url.destinationURL", ":destinationURL"))
                ->setParameter("destinationURL", "%" . $destinationURL . "%");
        }

        // Add active condition
        if (true === $onlyActive) {
            $queryBuilder->andWhere($queryBuilder->expr()->eq("short_url.active", true));
        }

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Generate instance of ShortURL.
     *
     * @param string|null $sourceName
     * @param int         $length
     * @return ShortURL
     */
    public function generateShortURL(?string $sourceName = null, int $length = 8): ShortURL {
        $sourceName = $sourceName ?? $this->generateSourceName($length);

        // Create ShortURL instance
        $shortURL = new ShortURL();
        $shortURL->setSourceName($sourceName);

        return $shortURL;
    }

    /**
     * Generate unique source name.
     *
     * @param int $length
     * @return string
     */
    public function generateSourceName(int $length) {
        do {
            $randomSourceName = ShortURLHelper::generateRandom($length);
        } while (null !== $this->getOneBySourceName($randomSourceName));

        return $randomSourceName;
    }

    /**
     * Save provided ShortURL.
     *
     * @param ShortURL $shortURL
     * @return bool
     */
    public function saveShortURL(ShortURL $shortURL): bool {
        try {
            $this->getEntityManager()->persist($shortURL);
            $this->getEntityManager()->flush();

            return true;
        } catch (Exception $exception) {
            return false;
        }
    }
}
