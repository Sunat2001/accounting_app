<?php

namespace App\Models;

use App\DTO\PaginationDTO;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $title
 * @property int $amount
 *
 * @property User $author
 */
class Transaction extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'title',
        'amount',
        'author_id'
    ];

    protected $casts = [
        'amount' => 'int'
    ];

    /**
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public static function getPaginationCacheKey(PaginationDTO $paginationDTO): string
    {
        return config('transaction.cache.prefix').$paginationDTO->getCacheKey();
    }

    public static function getPaginationCacheTag(): string
    {
        return config('transaction.cache.prefix');
    }
}
