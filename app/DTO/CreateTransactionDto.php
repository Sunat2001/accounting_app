<?php

namespace App\DTO;

class CreateTransactionDto
{
    public function __construct(
        public string $title,
        public int  $amount,
        public int $authorId
    ){}
}
