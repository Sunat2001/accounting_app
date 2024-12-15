<?php

namespace App\Repositories;

use App\DTO\CreateTransactionDto;
use App\DTO\PaginationDTO;
use App\Filters\TransactionListFilter;
use App\Models\Transaction;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Cache;

class TransactionRepositoryEloquent implements TransactionRepositoryInterface
{

    /**
     * @param PaginationDTO $paginationDTO
     * @param TransactionListFilter $filters
     * @return array
     */
    public function getAll(PaginationDTO $paginationDTO, TransactionListFilter $filters): array
    {
        $query = Transaction::query()->with('author')
        ->where('user_id', $paginationDTO->user->id);

        foreach ($paginationDTO->sort as $key => $value) {
            $query->orderBy($key, $value);
        }

        $query->when($filters->getAmount(),
            fn (Builder $query, int $amount) => $query->where('amount', $filters->getAmountFilterType(), $amount));

        $query->when($filters->getDate(), fn(Builder $query, string $date) => $query->where('created_at', $date));

        return Cache::tags(Transaction::getPaginationCacheTag())->remember(
            Transaction::getPaginationCacheKey($paginationDTO),
            config('pagination.cache.expiration'),
            static function () use ($query, $paginationDTO) {
                return $query->offset(($paginationDTO->page - 1) * $paginationDTO->size)
                    ->limit($paginationDTO->size)->get()->toArray();
            }
        );
    }

    /**
     * @param CreateTransactionDto $transactionDto
     * @return Transaction
     */
    public function create(CreateTransactionDto $transactionDto): Transaction
    {
        return Transaction::query()->create([
            'title' => $transactionDto->title,
            'amount' => $transactionDto->amount,
            'author_id' => $transactionDto->authorId
        ]);
    }
}
