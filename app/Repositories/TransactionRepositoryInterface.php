<?php

namespace App\Repositories;

use App\DTO\CreateTransactionDto;
use App\DTO\PaginationDTO;
use App\Filters\TransactionListFilter;
use App\Models\Transaction;

interface TransactionRepositoryInterface
{
    /**
     * Метод для выбора всех транзакций с учетом фильтров и сортировки
     * @param PaginationDTO $paginationDTO
     * @param TransactionListFilter $filters
     * @return array
     */
    public function getAll(PaginationDTO $paginationDTO, TransactionListFilter $filters): array;

    /**
     * @param CreateTransactionDto $transactionDto
     * @return Transaction
     */
    public function create(CreateTransactionDto $transactionDto): Transaction;
//    public function update();
//    public function delete();
//    public function find();
}
