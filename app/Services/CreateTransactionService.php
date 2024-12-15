<?php

namespace App\Services;

use App\DTO\CreateTransactionDto;
use App\Events\CreateTransactionEvent;
use App\Jobs\SendTransactionEmailJob;
use App\Models\Transaction;
use App\Repositories\TransactionRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateTransactionService
{
    public function __construct(
        private readonly TransactionRepositoryInterface $transactionRepository,
    ){}

    /**
     * @param Request $request
     * @return Transaction
     * @throws \Exception
     */
    public function execute(Request $request): Transaction
    {
        try {
            DB::beginTransaction();
            $transactionDto = new CreateTransactionDto(
                title: $request->get('title'),
                amount: $request->get('amount'),
                authorId: $request->user()->id,
            );

            $response = $this->transactionRepository->create($transactionDto);

            SendTransactionEmailJob::dispatch($request->user()->email);

            event(new CreateTransactionEvent($response));

            DB::commit();
            return $response;
        } catch (\Exception $exception) {
            DB::rollBack();

            Log::error($exception->getMessage(), [
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ]);
            throw $exception;
        }

    }
}
