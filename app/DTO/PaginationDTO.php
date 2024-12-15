<?php

namespace App\DTO;

use App\Models\User;

class PaginationDTO
{
    public function __construct(
        public readonly User $user,
        public readonly array $sort,
        public readonly int $page,
        public readonly int $size,
    ) {}

    public function getCacheKey(): string
    {
        $sort = implode('_', array_map(function ($value, $key) {
            return $key.'-'.$value;
        }, $this->sort, array_keys($this->sort)));
        return implode('_', [$this->user->id, $sort, $this->page, $this->size]);
    }
}
