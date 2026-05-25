<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Cart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $identifier = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\OneToMany(mappedBy: 'cart', targetEntity: CartItem::class, cascade: ['persist', 'remove'])]
    private Collection $cartItems;

    public function __construct()
    {
        $this->cartItems = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }
    public function getIdentifier(): ?string { return $this->identifier; }
    public function setIdentifier(string $identifier): static { $this->identifier = $identifier; return $this; }
    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }
    public function getCartItems(): Collection { return $this->cartItems; }
    public function addCartItem(CartItem $cartItem): static
    {
        if (!$this->cartItems->contains($cartItem)) {
            $this->cartItems->add($cartItem);
            $cartItem->setCart($this);
        }
        return $this;
    }
    public function removeCartItem(CartItem $cartItem): static
    {
        $this->cartItems->removeElement($cartItem);
        return $this;
    }
    public function getTotal(): float
    {
        $total = 0;
        foreach ($this->cartItems as $item) {
            $total += $item->getPrice() * $item->getQuantity();
        }
        return $total;
    }
}