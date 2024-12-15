<?php

namespace App\Http\Controllers;

use App\DTO\CreateTransactionDto;
use App\Filters\TransactionListFilter;
use App\Http\Requests\Transaction\TransactionIndexRequest;
use App\Http\Resources\Transaction\TransactionResource;
use App\Jobs\SendTransactionEmailJob;
use App\Models\Transaction;
use App\Repositories\TransactionRepositoryInterface;
use App\Services\CreateTransactionService;
use App\Services\PaginationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TransactionController extends Controller
{
    public function __construct(
        private readonly PaginationService $paginationService,
        private readonly TransactionRepositoryInterface $transactionRepository,
    ){}

    public function index(TransactionIndexRequest $request): JsonResponse
    {
        $paginationDto = $this->paginationService->getPaginationDTO($request, ['id']);

        $filters = new TransactionListFilter();
        $filters->setAmount($request->get('amount'));
        $filters->setDate($request->get('date'));
        $filters->setAmountFilterType($request->get('amount_filter_type'));

        $data = $this->transactionRepository->getAll($paginationDto, $filters);

        return response()->json([
            'data' => $data
        ]);
    }

    public function show(Transaction $transaction): TransactionResource
    {
        return new TransactionResource($transaction->load('author'));
    }

    public function create(Request $request): JsonResponse
    {
        $request->validate([
            'title' => ['required', 'string'],
            'amount' => ['required', 'integer', 'min:1'],
        ]);

        /** @var CreateTransactionService $service */
        $service = app(CreateTransactionService::class);
        $result = $service->execute($request);

        return response()->json($result, 201);
    }

    public function delete(Transaction $transaction): Response
    {
        $transaction->delete();

        return response()->noContent();
    }
}
