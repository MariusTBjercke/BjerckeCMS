<?php

namespace App\Repository;

use App\Entity\Games\Marker;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use RuntimeException;

/**
 * @extends ServiceEntityRepository<Marker>
 *
 * @method Marker|null find($id, $lockMode = null, $lockVersion = null)
 * @method Marker|null findOneBy(array $criteria, array $orderBy = null)
 * @method Marker[]    findAll()
 * @method Marker[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MarkerRepository extends ServiceEntityRepository {
    /**
     * Constructor.
     *
     * @param ManagerRegistry $registry Registry.
     */
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Marker::class);
    }

    /**
     * Add.
     *
     * @param Marker $entity Entity.
     * @param boolean $flush Flush.
     * @return void
     */
    public function add(Marker $entity, bool $flush = false): void {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Remove.
     *
     * @param Marker $entity Entity.
     * @param boolean $flush Flush.
     * @return void
     */
    public function remove(Marker $entity, bool $flush = false): void {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Find by ID or throw an exception.
     *
     * @param string $id Map ID.
     * @return Marker
     */
    public function findByIdOrThrow(string $id): Marker {
        $entity = $this->findOneBy(['id' => $id]);

        if (!$entity) {
            throw new RuntimeException(sprintf('Marker ID "%s" not found.', $id));
        }

        return $entity;
    }
}
