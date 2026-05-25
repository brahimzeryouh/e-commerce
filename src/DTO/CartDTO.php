<?php
namespace App\DTO;

class CartDTO
{
    private string $sessionId;
    private array $items = [];
    private float $total = 0.0;
    private \DateTimeImmutable $createdAt;

    public function __construct(string $sessionId)
    {
        $this->sessionId = $sessionId;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getSessionId(): string { return $this->sessionId; }

    public function getItems(): array { return $this->items; }

    public function addItem(int $productId, string $productName, int $quantity, float $price): void
    {
        $this->items[] = [
            'product_id' => $productId,
            'product_name' => $productName,
            'quantity' => $quantity,
            'price' => $price,
            'total' => $quantity * $price
        ];
        $this->recalculateTotal();
    }

    public function removeItem(int $productId): void
    {
        foreach ($this->items as $index => $item) {
            if ($item['product_id'] === $productId) {
                array_splice($this->items, $index, 1);
                break;
            }
        }
        $this->recalculateTotal();
    }

    public function updateQuantity(int $productId, int $quantity): void
    {
        foreach ($this->items as &$item) {
            if ($item['product_id'] === $productId) {
                $item['quantity'] = $quantity;
                $item['total'] = $quantity * $item['price'];
                break;
            }
        }
        $this->recalculateTotal();
    }

    public function getTotal(): float { return $this->total; }

    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }

    public function getItemCount(): int
    {
        return array_sum(array_column($this->items, 'quantity'));
    }

    private function recalculateTotal(): void
    {
        $this->total = array_sum(array_column($this->items, 'total'));
    }

    public function toArray(): array
    {
        return [
            'sessionId' => $this->sessionId,
            'items' => $this->items,
            'total' => $this->total,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'itemCount' => $this->getItemCount()
        ];
    }

    public static function fromArray(array $data): self
    {
        $dto = new self($data['sessionId']);
        foreach ($data['items'] as $item) {
            $dto->addItem($item['product_id'], $item['product_name'], $item['quantity'], $item['price']);
        }
        return $dto;
    }
}