<?php

namespace App\Dto;

class UsersDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $username,
        public readonly string $firstname,
        public readonly string $lastname,
        public readonly string $email,
        public readonly ?string $number,
        public readonly array $roles,
        public readonly? string $picture,
        public readonly? string $contenthash
    ) {}
}