<?php

namespace App\DTOs;

use Illuminate\Support\Collection;

class CartDTO
{
    /**
     * @param Collection<CartItemDTO> $items
     */
    public function __construct(
        public Collection $items,
        public float $total,
    ) {}

    public static function fromArray(array $data): self
    {
        $items = collect($data['items'] ?? [])
            ->map(fn($item) => $item instanceof CartItemDTO ? $item : CartItemDTO::fromArray($item));

        return new self(
            items: $items,
            total: $data['total'] ?? 0,
        );
    }

    public function toArray(): array
    {
        return [
            'items' => $this->items->map(fn($item) => $item->toArray())->toArray(),
            'total' => $this->total,
        ];
    }

    public function getItemCount(): int
    {
        return $this->items->count();
    }

    public function isEmpty(): bool
    {
        return $this->items->isEmpty();
    }
}

