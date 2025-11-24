<?php

namespace App\DataTransferObjects\Store;
use Illuminate\Http\UploadedFile;

class StoreDTO

{
    public function __construct(
        public readonly string $name,
        public readonly ?string $description,
        public readonly ?UploadedFile $logo = null,
    ) {}

    public static function fromArray(array $data, ?UploadedFile $logo = null): self
    {
        return new self(
            name: $data['name'],
            description: $data['description'],
            logo: $logo,
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
