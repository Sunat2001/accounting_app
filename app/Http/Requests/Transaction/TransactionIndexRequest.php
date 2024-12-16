<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OAT;

class TransactionIndexRequest extends FormRequest
{
    #[OAT\Parameter(
        name: 'size',
        description: 'Сортировка данных по нескольким полям',
        in: 'query',
        schema: new OAT\Schema(type: 'integer'),
    )]
    #[OAT\Parameter(
        name: 'page',
        description: 'Номер страницы с данными',
        in: 'query',
        schema: new OAT\Schema(type: 'integer'),
    )]
    #[OAT\Parameter(
        name: 'sort',
        description: 'Сортировка данных по нескольким полям',
        in: 'query',
        required: false,
        schema: new OAT\Schema(
            properties: [
                new OAT\Property(
                    property: 'id',
                    type: 'enum',
                    default: 'desc',
                    enum: ['asc', 'desc']
                ),
            ],
            type: 'object',
        ),
        style: 'deepObject',
        explode: true,
    )]
    #[OAT\Parameter(
        name: 'amount_filter',
        description: 'Фильтр по сумме',
        in: 'query',
        schema: new OAT\Schema(type: 'integer'),
    )]
    #[OAT\Parameter(
        name: 'amount_filter_type',
        description: 'Тип фильтра по сумме',
        in: 'query',
        schema: new OAT\Schema(type: 'enum', enum: ['=', '>', '<', '<=']),
    )]
    #[OAT\Parameter(
        name: 'date_filter',
        description: 'Фильтр по дате',
        in: 'query',
        schema: new OAT\Schema(type: 'datetime', example: '2024-03-20 12:33:40'),
    )]
    public function rules(): array
    {
        return [
            'per_page' => ['int', 'between:1,25'],
            'size' => ['int', 'between:1,25'],
            'page' => ['int', 'gt:0'],
            'sort' => ['array'],
            'sort.*' => [Rule::in(['desc', 'asc'])],
            'amount_filter' => ['nullable', 'int'],
            'amount_filter_type' => [Rule::requiredIf('amount_filter'), 'string', Rule::in(['=', '>', '<', '<='])],
            'date_filter' => ['nullable', 'date', 'date_format:Y-m-d'],
        ];
    }
}
