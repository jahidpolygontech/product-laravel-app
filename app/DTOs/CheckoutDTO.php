<?php

namespace App\DTOs;

class CheckoutDTO
{
    public function __construct(
        public string $shipping_address,
        public string $phone,
        public ?string $name = null,
        public ?string $email = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            shipping_address: $data['shipping_address'],
            phone: $data['phone'],
            name: $data['name'] ?? null,
            email: $data['email'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'shipping_address' => $this->shipping_address,
            'phone' => $this->phone,
            'name' => $this->name,
            'email' => $this->email,
        ];
    }
}

