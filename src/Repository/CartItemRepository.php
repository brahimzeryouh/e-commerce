<?php

namespace App\Repository;

use App\Entity\CartItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CartItem>
 */
class CartItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CartItem::class);
    }

    /**
     * Trouve tous les articles d'un panier spécifique
     */
    public function findByCartId(int $cartId): array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.cart = :cartId')
            ->setParameter('cartId', $cartId)
            ->leftJoin('i.product', 'p')
            ->addSelect('p')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve un article spécifique dans un panier
     */
    public function findItemInCart(int $cartId, int $productId): ?CartItem
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.cart = :cartId')
            ->andWhere('i.product = :productId')
            ->setParameter('cartId', $cartId)
            ->setParameter('productId', $productId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Sauvegarde un article du panier
     */
    public function save(CartItem $item, bool $flush = true): void
    {
        $this->getEntityManager()->persist($item);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Supprime un article du panier
     */
    public function remove(CartItem $item, bool $flush = true): void
    {
        $this->getEntityManager()->remove($item);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Supprime tous les articles d'un panier
     */
    public function removeAllItemsByCartId(int $cartId): int
    {
        return $this->createQueryBuilder('i')
            ->delete()
            ->where('i.cart = :cartId')
            ->setParameter('cartId', $cartId)
            ->getQuery()
            ->execute();
    }

    /**
     * Met à jour la quantité d'un article
     */
    public function updateQuantity(int $cartId, int $productId, int $quantity): void
    {
        $this->createQueryBuilder('i')
            ->update()
            ->set('i.quantity', ':quantity')
            ->where('i.cart = :cartId')
            ->andWhere('i.product = :productId')
            ->setParameter('quantity', $quantity)
            ->setParameter('cartId', $cartId)
            ->setParameter('productId', $productId)
            ->getQuery()
            ->execute();
    }

    /**
     * Calcule le total d'un panier
     */
    public function getCartTotal(int $cartId): float
    {
        $result = $this->createQueryBuilder('i')
            ->select('SUM(i.price * i.quantity) as total')
            ->where('i.cart = :cartId')
            ->setParameter('cartId', $cartId)
            ->getQuery()
            ->getSingleScalarResult();

        return (float) ($result ?? 0);
    }
}