<?php

namespace App\DTOs;

class CartItemDTO
{
    public function __construct(
        public int $id,
        public int $product_id,
        public string $product_name,
        public float $price,
        public int $quantity,
        public float $subtotal,
        public ?object $product = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? 0,
            product_id: $data['product_id'] ?? ($data['product']->id ?? 0),
            product_name: $data['product_name'] ?? ($data['product']->name ?? ''),
            price: (float) $data['price'],
            quantity: (int) $data['quantity'],
            subtotal: $data['subtotal'] ?? ((float) $data['price'] * (int) $data['quantity']),
            product: $data['product'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'product_name' => $this->product_name,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'subtotal' => $this->subtotal,
            'product' => $this->product,
        ];
    }
}
