<?php

namespace App\Repository;

use App\Entity\Games\GameMap;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use RuntimeException;

/**
 * @extends ServiceEntityRepository<GameMap>
 *
 * @method GameMap|null find($id, $lockMode = null, $lockVersion = null)
 * @method GameMap|null findOneBy(array $criteria, array $orderBy = null)
 * @method GameMap[]    findAll()
 * @method GameMap[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameMapRepository extends ServiceEntityRepository {
    /**
     * Constructor.
     *
     * @param ManagerRegistry $registry Registry.
     */
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, GameMap::class);
    }

    /**
     * Add.
     *
     * @param GameMap $entity Entity.
     * @param boolean $flush Flush.
     * @return void
     */
    public function add(GameMap $entity, bool $flush = false): void {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Save entity.
     *
     * @return void
     */
    public function save(): void {
        $this->getEntityManager()->flush();
    }

    /**
     * Remove.
     *
     * @param GameMap $entity Entity.
     * @param boolean $flush Flush.
     * @return void
     */
    public function remove(GameMap $entity, bool $flush = false): void {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Find by ID or throw an exception.
     *
     * @param string $id Map ID.
     * @return GameMap
     */
    public function findByIdOrThrow(string $id): GameMap {
        $entity = $this->findOneBy(['id' => $id]);

        if (!$entity) {
            throw new RuntimeException(sprintf('Game map ID "%s" not found.', $id));
        }

        return $entity;
    }

    //    /**
    //     * @return GameMap[] Returns an array of GameMap objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('g.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?GameMap
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
