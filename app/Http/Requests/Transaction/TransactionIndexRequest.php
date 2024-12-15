<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TransactionIndexRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
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
