<?php

namespace App\Http\Controllers;

use App\Filters\TransactionListFilter;
use App\Http\Requests\Transaction\TransactionIndexRequest;
use App\Http\Resources\Transaction\TransactionResource;
use App\Models\Transaction;
use App\Repositories\TransactionRepositoryInterface;
use App\Services\CreateTransactionService;
use App\Services\PaginationService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseCode;
use Illuminate\Validation\ValidationException;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Transactions',
    description: 'Transactions group api',
)]
class TransactionController extends Controller
{
    public function __construct(
        private readonly PaginationService              $paginationService,
        private readonly TransactionRepositoryInterface $transactionRepository,
    )
    {
    }

    /**
     * @throws ValidationException
     */
    #[OA\Get(
        path: "transaction",
        summary: "List all transactions with filters",
        tags: ["Transactions"],
        parameters: [
            new OA\Parameter(ref: "#/components/parameters/size"),
            new OA\Parameter(ref: "#/components/parameters/page"),
            new OA\Parameter(ref: "#/components/parameters/sort"),
            new OA\Parameter(ref: "#/components/parameters/amount_filter"),
            new OA\Parameter(ref: "#/components/parameters/amount_filter_type"),
            new OA\Parameter(ref: "#/components/parameters/date_filter"),
        ],
        responses: [
            new OA\Response(
                response: ResponseCode::HTTP_OK,
                description: 'OK',
                content: new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(
                                property: 'data',
                                type: 'array',
                                items: new OA\Items(ref: Transaction::class),
                            ),
                            new OA\Property(
                                property: 'meta',
                                properties: [
                                    new OA\Property(
                                        property: 'page',
                                        description: 'Номер страницы с данными',
                                        type: 'integer',
                                        example: 1
                                    ),
                                    new OA\Property(
                                        property: 'size',
                                        description: 'Размер страницы с данными',
                                        type: 'integer',
                                        example: 10,
                                    ),
                                    new OA\Property(
                                        property: 'sort',
                                        description: 'Сортировка данных по нескольким полям',
                                        type: 'object',
                                        additionalProperties: new OA\AdditionalProperties(
                                            type: 'enum',
                                            enum: ['asc', 'desc']
                                        )
                                    ),
                                    new OA\Property(
                                        property: 'total',
                                        description: 'Общее количество',
                                        type: 'integer',
                                        example: 100,
                                    ),
                                ],
                                type: 'object',
                            )
                        ],
                    ),
                )
            ),
        ],
    )]
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

    #[OA\Get(
        path: "transaction/{transactionId}",
        summary: "Get transactions by id",
        tags: ["Transactions"],
        parameters: [
            new OA\Parameter(
                name: "transactionId",
                description: "ID of the transaction to delete",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(ref: '#/components/responses/TransactionSuccessResponse', response: ResponseCode::HTTP_OK),
            new OA\Response(ref: '#/components/responses/ServerErrorResponse',
                response: ResponseCode::HTTP_INTERNAL_SERVER_ERROR),
        ]
    )]
    public function show(Transaction $transaction): TransactionResource
    {
        return new TransactionResource($transaction->load('author'));
    }

    /**
     * @throws Exception
     */
    #[OA\Post(
        path: "transaction",
        summary: "Create new transaction",
        requestBody: new OA\RequestBody(
            description: "Create new transaction body",
            required: true,
            content: [
                new OA\JsonContent(
                    required: ['title', 'amount'],
                    properties: [
                        new OA\Property(
                            property: "title",
                            description: "Transaction title",
                            type: "string",
                            example: "Transaction title"
                        ),
                        new OA\Property(
                            property: "amount",
                            description: "Transaction amount",
                            type: "number",
                            example: "24"
                        )
                    ]
                )
            ],
        ),
        tags: ["Transactions"],
        responses: [
            new OA\Response(ref: '#/components/responses/TransactionSuccessResponse', response: ResponseCode::HTTP_CREATED),
            new OA\Response(ref: '#/components/responses/UnprocessableEntityResponse',
                response: ResponseCode::HTTP_UNPROCESSABLE_ENTITY),
            new OA\Response(ref: '#/components/responses/ServerErrorResponse',
                response: ResponseCode::HTTP_INTERNAL_SERVER_ERROR),
        ]
    )]
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

    #[OA\Delete(
        path: "transaction/{transactionId}",
        summary: "Delete transaction by id",
        tags: ["Transactions"],
        parameters: [
            new OA\Parameter(
                name: "transactionId",
                description: "ID of the transaction to delete",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: "No Content"
            ),
        ]
    )]
    public function delete(Transaction $transaction): Response
    {
        $transaction->delete();

        return response()->noContent();
    }
}
