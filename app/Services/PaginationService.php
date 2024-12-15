<?php

namespace App\Services;

use App\DTO\PaginationDTO;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PaginationService
{
    public function getPaginationDTO(Request $request, array $sortColumns = []): PaginationDTO
    {
        $sort = $request->input('sort', []);
        $errors = [];
        foreach ($sort as $key => $value) {
            if (!in_array(strtolower($key), $sortColumns)) {
                $errors['sort.'.$key] = 'cannot sort by column';
            }
        }
        if (!empty($errors)) {
            // TODO: требуется локализация
            throw ValidationException::withMessages($errors);
        }

        return new PaginationDTO(
            user: $request->user(),
            sort: $request->input('sort', config('pagination.sort')),
            page: (int) $request->input('page', config('pagination.page')),
            size: (int) ($request->input('per_page') ?? $request->input('size') ?? config('pagination.limit'))
        );
    }
}
