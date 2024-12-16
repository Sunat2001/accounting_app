<?php

namespace App\Models;

use App\DTO\PaginationDTO;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OpenApi\Attributes as OA;

/**
 * @property int $id
 * @property string $title
 * @property int $amount
 *
 * @property User $author
 */
#[OA\Schema(
    description: 'Transactions',
    required: [
        'title',
        'amount',
        'created_at',
    ],
    properties: [
        new OA\Property(
            property: 'id',
            type: 'int'
        ),
        new OA\Property(
            property: 'title',
            type: 'string'
        ),
        new OA\Property(
            property: 'amount',
            type: 'int'
        ),
        new OA\Property(
            property: 'created_at',
            type: 'datetime'
        ),
        new OA\Property(
            property: 'updated_at',
            type: 'datetime'
        ),
    ],
    type: 'object',
)]
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

    #[OA\Response(
        response: 'TransactionSuccessResponse',
        description: 'OK',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                required: ['data'],
                properties: [
                    new OA\Property(
                        property: 'data',
                        ref: self::class,
                    )
                ],
            ),
        ),
    )]
    public static function getPaginationCacheKey(PaginationDTO $paginationDTO): string
    {
        return config('transaction.cache.prefix').$paginationDTO->getCacheKey();
    }

    public static function getPaginationCacheTag(): string
    {
        return config('transaction.cache.prefix');
    }
}
