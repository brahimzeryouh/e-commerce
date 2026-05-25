<?php

namespace App\Repository;

use App\Entity\Cart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cart>
 */
class CartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cart::class);
    }

    /**
     * Trouve un panier par son identifiant de session
     */
    public function findBySessionId(string $sessionId): ?Cart
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.sessionId = :sessionId')
            ->setParameter('sessionId', $sessionId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Sauvegarde un panier en base de données
     */
    public function save(Cart $cart, bool $flush = true): void
    {
        $this->getEntityManager()->persist($cart);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Supprime un panier
     */
    public function remove(Cart $cart, bool $flush = true): void
    {
        $this->getEntityManager()->remove($cart);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Trouve tous les paniers avec leurs articles
     */
    public function findAllWithItems(): array
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.items', 'i')
            ->addSelect('i')
            ->getQuery()
            ->getResult();
    }

    /**
     * Supprime les paniers inactifs (plus vieux que X jours)
     */
    public function deleteInactiveCarts(int $days = 30): int
    {
        $date = new \DateTimeImmutable("-$days days");

        return $this->createQueryBuilder('c')
            ->delete()
            ->where('c.createdAt < :date')
            ->setParameter('date', $date)
            ->getQuery()
            ->execute();
    }
}