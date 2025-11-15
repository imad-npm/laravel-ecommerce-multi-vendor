<?php

namespace App\DataTransferObjects\Profile;

use Illuminate\Http\Request;

class UpdateProfileDTO 
{
    public function __construct(
        public readonly ?string $name,
        public readonly ?string $email,
    ) {}

   public static function fromRequest(Request $request): self
{
    $data = $request->only(['name', 'email']);

    return new self(
        name: $data['name'] ?? null,
        email: $data['email'] ?? null,
    );
}


}
