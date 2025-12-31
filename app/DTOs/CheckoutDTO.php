<?php

namespace App\DTOs;

class CheckoutDTO
{
    public function __construct(
        public readonly ?string $name,
        public readonly ?string $email,
        public readonly string $shipping_address,
        public readonly string $phone,
    ) {}

    /**
     * Create DTO from validated request data
     */
    public static function fromRequest(array $data): self
    {
        return new self(
            name: $data['name'] ?? null,
            email: $data['email'] ?? null,
            shipping_address: $data['shipping_address'],
            phone: $data['phone'],
        );
    }

    /**
     * Optional: Create DTO from array (generic)
     */
    public static function fromArray(array $data): self
    {
        return self::fromRequest($data);
    }
}
