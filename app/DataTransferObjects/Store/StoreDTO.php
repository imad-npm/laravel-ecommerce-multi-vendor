<?php

namespace App\DataTransferObjects\Store;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class StoreDTO
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $description,
        public readonly ?UploadedFile $logo = null,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            name: $request->input('name'),
            description: $request->input('description'),
            logo: $request->file('logo'),
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
        ];
    }
}
