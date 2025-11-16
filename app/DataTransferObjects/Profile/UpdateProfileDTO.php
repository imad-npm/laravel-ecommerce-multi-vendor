<?php

namespace App\DataTransferObjects\Profile;

class UpdateProfileDTO 
{
    public function __construct(
        public readonly ?string $name,
        public readonly ?string $email,
    ) {}

   public static function fromArray(array $data): self
{
    return new self(
        name: $data['name'] ?? null,
        email: $data['email'] ?? null,
    );
}


}
