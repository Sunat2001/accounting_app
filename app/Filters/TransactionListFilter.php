<?php

namespace App\Filters;

class TransactionListFilter
{
    private ?int $amount;
    private ?string $amountFilterType;
    private ?string $date;

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(?string $amount): TransactionListFilter
    {
        $this->amount = $amount;
        return $this;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(?string $date): TransactionListFilter
    {
        $this->date = $date;
        return $this;
    }

    public function getAmountFilterType(): ?string
    {
        return $this->amountFilterType;
    }

    public function setAmountFilterType(?string $amountFilterType): TransactionListFilter
    {
        $this->amountFilterType = $amountFilterType;
        return $this;
    }
}
